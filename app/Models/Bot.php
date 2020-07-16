<?php

namespace Models;

use Bot\AutoListener;
use Bot\AutoLoader;
use Bot\Connection;
use Bot\Console\Application;
use Bot\Console\InputUtils;
use Bot\Event\ChatEvent;
use Bot\Event\PersonChatEvent;
use Bot\Exception\NetworkException;
use Bot\Extensions\AsyncInfoExtension;
use Bot\Extensions\CodeExtension;
use Bot\Handle;
use Bot\Handler;
use Bot\Listenerable;
use Bot\Packet;
use Bot\Packets\ChatPacket;
use Bot\Packets\PersonChatPacket;
use Bot\Packets\PingPacket;
use Console\Commands\Command;
use Console\ErrorFormat;
use Exceptions\NeedAuthException;
use File\File;
use Logger\Logger;
use Model\Models\Model;
use SplQueue;
use Swoole\ExitException;
use Symfony\Component\Console\Output\BufferedOutput;
use Throwable;

/**
 * Class Bot
 * @package Models
 * @adminphp start
 * @property $id
 * @property $uid
 * @property $username
 * @property $password
 * @property $room
 * @property $token
 * @property $enable
 * @method static Bot find(int $id)
 * @method static Bot findOrFail(int $id)
 * @method static \Illuminate\Database\Query\Builder where(\Closure|string|array $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @adminphp end
 */
class Bot extends Model implements Listenerable
{
    public $timestamps = false;

    /**
     * @param $token
     * @return Bot
     */
    public static function auth($token)
    {
        /** @var Bot $bot */
        $bot = self::where('token', '=', $token)->first();
        return $bot;
    }

    /**
     * @param $token
     * @return Bot
     */
    public static function authOrFail($token)
    {
        $bot = self::auth($token);
        if ($bot) {
            return $bot;
        } else {
            throw new NeedAuthException();
        }
    }

    use AutoListener;
    use AsyncInfoExtension;
    use CodeExtension;

    /** @var Bot $instance */
    public static $instance;
    public $startAt;
    /** @var SplQueue $queue */
    protected $receiveQueue;
    /** @var SplQueue $queue */
    protected $sendQueue;
    /** @var Connection $connect */
    protected $connect;
    /** @var Handle[] $handles */
    protected $handles;
    /** @var Application $command */
    protected $command;
    /** @var \Bot\Command[] $commands */
    protected $commands;
    /** @var BotPlugin[] $plugins */
    protected $plugins=[];
    /** @var AutoLoader $autoLoader */
    protected $autoLoader;

    public function run()
    {
        Logger::info('构造');
        self::$instance = $this;
        $this->startAt = time();
        $this->receiveQueue = new SplQueue();
        $this->sendQueue = new SplQueue();
        $this->autoLoader = new AutoLoader();

        Logger::info('载入加载器');
        $files = File::scan_dir_files(ROOT . '/app/Bot/Handler/', false);
        foreach ($files as $file) {
            $handler_class = '\\Bot\\Handler\\' . substr($file, 0, strlen($file) - 4);
            /** @var \ReflectionClass $handler_class */
            $handler = new $handler_class();
            $this->handles[substr($handler_class, 1)] = new Handle($handler);
        }
        Logger::info('载入命令解析器');
        $this->loadCommand();

        Logger::info('监听事件');
        $this->registerListeners();

        Logger::info('载入时钟');
        go(function () {
            $this->plugin();
        });
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
    }

    private function plugin()
    {
        try {
            while(true) {
                try {
                    $plugins_list = BotPlugin::findByBot($this);
                    $plugins=[];
                    foreach ($plugins_list as $plugin){
                        $plugins[$plugin->id]=$plugin;
                    }
                    foreach ($plugins as $plugin) {
                        if(!isset($this->plugins[$plugin->id])){
                            $this->plugins[$plugin->id]=$plugin;
                            $this->plugins[$plugin->id]->loading($this);
                        }
                    }
                    foreach ($this->plugins as $plugin) {
                        if(!isset($plugins[$plugin->id])){
                            unset($this->plugins[$plugin->id]);
                        }
                    }
                }catch (Throwable $e){
                    ErrorFormat::dump($e);
                }
                \Co::sleep(5);
            }
        } catch (NetworkException $e) {
            ErrorFormat::dump($e);
        }
    }

    private function login()
    {
        $this->connect = new Connection($this->username, $this->password);
        try {
            $this->connect->login($this->room, function ($packet) {
                $this->receiveQueue->push($packet);
            });
        } catch (NetworkException $e) {
            ErrorFormat::dump($e);
        }
    }

    private function receive()
    {
        while (true) {
            if ($this->receiveQueue->count()) {
                $this->solve($this->receiveQueue->pop());
            }
            \Co::sleep(0.1);
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
                    } catch (\Throwable $e) {
                    }
                    \Co::sleep(5);
                }
            }
            \Co::sleep(0.1);
        }
    }

    private function protect()
    {
        $this->login();
        while (true) {
            \Co::sleep(0.1);
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
            5000, function () {
            $this->packet(new PingPacket());
        }
        );
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
        //var_dump($message);
        go(function () use ($message) {
            $firstChar = substr($message, 0, 1);
            $explode = self::decode(explode('>', $message));
            $count = count($explode);
            foreach ($this->handles as $handle) {
                $handle->onPacket($message, $firstChar, $count, $explode);
            }
        });
    }

    public function packet(Packet $packet)
    {
        $this->sendQueue->push($packet->compile());
    }

    public function getAutoLoader()
    {
        return $this->autoLoader;
    }

    public function getHandler($handleClass)
    {
        return $this->handles[$handleClass];
    }

    private function loadCommand(){
        $this->command = new Application('IIROSE-BOT-' . $this->username, '瞄呜');
    }
    public function addCommand($configure)
    {
        $this->commands[$configure->sign] = new \Bot\Command($configure);
        $this->command->add($this->commands[$configure->sign]);
    }
    public function removeCommand($configure)
    {
        unset($this->commands[$configure->sign]);
        $this->loadCommand();
        foreach ($this->commands as $command){
            $this->command->add($command);
        }
    }
    public function setRoom($room)
    {
        $this->room = $room;
        $this->save();
    }


    public function onChat(ChatEvent $chatEvent)
    {
        if (substr($chatEvent->message, 0, 1) == '/') {
            $output = new BufferedOutput();
            try {
                $this->command->run(new InputUtils(substr($chatEvent->message, 1), $chatEvent), $output);
            } catch (ExitException $e) {

            } catch (Throwable $e) {
                ErrorFormat::dump($e);
            }
            $d = $output->fetch();
            if (strlen($d)) {
                $this->packet(new ChatPacket('\\\\\\=' . $d, $chatEvent->color ?: null));
            }
        }
    }

    public function onPersonChat(PersonChatEvent $personChatEvent)
    {
        if (substr($personChatEvent->message, 0, 1) == '/') {
            $output = new BufferedOutput();
            try {
                Bot::$instance->command->run(new InputUtils(substr($personChatEvent->message, 1), $personChatEvent), $output);
            } catch (ExitException $e) {

            } catch (Throwable $e) {
                ErrorFormat::dump($e);
            }
            $d = $output->fetch();
            if (strlen($d)) {
                $this->packet(new PersonChatPacket($personChatEvent->user_id, '\\\\\\=' . $output->fetch(), $personChatEvent->color ?: null));
            }
        }
    }

    public function loaded()
    {
        return true;
    }

}