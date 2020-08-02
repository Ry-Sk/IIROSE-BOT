<?php
namespace Bot\Provider\MiraiApiHttp;

use Bot\Exception\NetworkException;
use Bot\Extensions\AutoListener;
use Bot\Extensions\SyncInfoExtension;
use Bot\Packets\PersonChatPacket;
use Bot\Provider\IIROSE\Extensions\CodeExtension;
use Bot\Provider\IIROSE\Packets\ChatPacket;
use Bot\Provider\IIROSE\Packets\PingPacket;
use Bot\Provider\MiraiApiHttp\Api\AuthApi;
use Bot\Provider\MiraiApiHttp\Api\ReleaseApi;
use Bot\Provider\MiraiApiHttp\Api\SendFriendMessageApi;
use Bot\Provider\MiraiApiHttp\Api\SendGroupMessageApi;
use Bot\Provider\MiraiApiHttp\Api\VerifyApi;
use Bot\Provider\Provider;
use Co;
use Console\ErrorFormat;
use Logger\Logger;
use Models\Bot;
use SplQueue;
use Throwable;

class MiraiApiHttpProvider extends Provider
{
    use AutoListener;
    public static $instance;
    public $session;
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
        Logger::info('载入循环泵');
        go(function () {
            $this->protect();
        });
        go(function () {
            $this->receive();
        });
        go(function () {
            //$this->send();
        });
        go(function () {
            //$this->timer();
        });

    }
    private function login()
    {
        if($this->session){
            $response = (new ReleaseApi())
                ->setSession($this->session)
                ->setQq($this->bot->username)->push();
            if($response->code != 0){
                Logger::warn('Release失败：'.$response->code.'：'.$response->msg);
                return;
            }
        }
        $response = (new AuthApi())->setAuthKey($this->bot->password)->push();
        if($response->code != 0){
            Logger::warn('Auth失败：'.$response->code);
            return;
        }
        $this->session=$response->session;
        $response = (new VerifyApi())->setQq($this->bot->username)->push();
        if($response->code != 0){
            Logger::warn('Verify失败：'.$response->code.'：'.$response->msg);
            return;
        }
        $this->connect = new Connection();
        try {
            $this->connect->login(function ($packet) {
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

            }
        );
    }
    private function solve($message)
    {
        $message=json_decode($message,true);
        /** @var Resolver $resolverClass */
        $resolverClass='Bot\\Provider\\MiraiApiHttp\\Resolver\\'.$message['type'].'Resolver';
        if(class_exists($resolverClass)){
            $event=$resolverClass::pharse($message);
            $this->bot->event($event);
        }else{
            Logger::info('未知的包：'.$message['type']);
        }
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
        $response = (new SendGroupMessageApi())->setTarget($room_id)->messageSetPlain($message)->push();
        if($response->code != 0){
            Logger::warn('SendGroupMessage失败：'.$response->code.'：'.$response->msg);
        }
    }

    public function sendPersonChat($user_id, $message)
    {
        $response = (new SendFriendMessageApi())->setTarget($user_id)->messageSetPlain($message)->push();
        if($response->code != 0){
            Logger::warn('SendFriendMessage失败：'.$response->code.'：'.$response->msg);
            return;
        }
    }
}