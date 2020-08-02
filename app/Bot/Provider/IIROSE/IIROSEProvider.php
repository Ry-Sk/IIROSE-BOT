<?php
namespace Bot\Provider\IIROSE;

use Bot\Exception\NetworkException;
use Bot\Extensions\AutoListener;
use Bot\Extensions\SyncInfoExtension;
use Bot\Packets\PersonChatPacket;
use Bot\Provider\IIROSE\Extensions\CodeExtension;
use Bot\Provider\IIROSE\Packets\ChatPacket;
use Bot\Provider\IIROSE\Packets\PingPacket;
use Bot\Provider\Provider;
use Co;
use Console\ErrorFormat;
use Logger\Logger;
use Models\Bot;
use SplQueue;
use Throwable;

class IIROSEProvider extends Provider
{
    use SyncInfoExtension;
    use AutoListener;
    use CodeExtension;
    use SyncInfoExtension;
    public static $instance;
    /** @var Bot $bot */
    private $bot;
    /** @var Resolve $resolve */
    private $resolve;
    /** @var Connection $connect */
    protected $connect;
    /** @var SplQueue $queue */
    protected $receiveQueue;
    /** @var SplQueue $queue */
    protected $sendQueue;
    public function __construct(Bot $bot)
    {
        Logger::info('构造应用');
        $this->receiveQueue=new SplQueue();
        $this->sendQueue=new SplQueue();
        self::$instance=$this;
        $this->bot=$bot;
        Logger::info('载入解析器');
        $this->resolve = new Resolve();
        go(function () {
            $this->protect();
        });
        go(function () {
            $this->receive();
        });
        go(function () {
            $this->send();
        });
        go(function () {
            $this->timer();
        });
        $this->registerListener();
    }
    private function login()
    {
        $this->connect = new Connection($this->bot->username, $this->bot->password);
        try {
            $this->connect->login($this->bot->room, function ($packet) {
                $this->receiveQueue->push($packet);
            });
        } catch (NetworkException $e) {
            ErrorFormat::dump($e);
        }
    }
    private function send()
    {
        while (true) {
            if ($this->sendQueue->count()) {
                $data = $this->sendQueue->pop();
                while (true) {
                    try {
                        if ($this->connect->send($data)) {
                            break;
                        }
                    } catch (Throwable $e) {
                    }
                    Co::sleep(5);
                }
            }
            Co::sleep(0.1);
        }
    }
    private function receive()
    {
        while (true) {
            if ($this->receiveQueue->count()) {
                $this->solve($this->receiveQueue->pop());
            }
            Co::sleep(0.1);
        }
    }
    private function protect()
    {
        $this->login();
        while (true) {
            Co::sleep(0.1);
            if (@$this->connect->alive()) {
                continue;
            }
            Logger::warn('连接丢失，尝试重连接');
            if ($this->connect) {
                $this->connect->close();
            }
            $this->login();
        }
    }

    private function timer()
    {
        swoole_timer_tick(
            5000,
            function () {
                $this->packet(new PingPacket());
            }
        );
    }
    public function packet(Packet $packet)
    {
        $this->sendQueue->push($packet->compile());
    }
    private function solve($message)
    {
        $s = @gzdecode(substr($message, 1)) ?: $message;
        $dump = explode('<', $s);
        foreach ($dump as $p) {
            $this->parse($p);
        }
    }

    private function parse(string $message)
    {
        go(function () use ($message) {
            $event = $this->resolve->resolve($message);
            if($event){
                $this->bot->event($event);
            }
        });
    }

    public function loaded()
    {
        return true;
    }

    public function getGroupList()
    {
        return [$this->bot->room];
    }

    public function sendRoomChat($room_id, $message)
    {
        if($room_id==$this->bot->room){
            $this->packet(new ChatPacket($message));
        }else {
            Logger::warn('room_id不符');
        }
    }

    public function sendPersonChat($user_id, $message)
    {
        $this->packet(new PersonChatPacket($user_id,$message));
    }
}