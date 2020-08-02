<?php

namespace Models;

use Bot\AutoLoader;
use Bot\Console\Application;
use Bot\Console\InputUtils;
use Bot\Event\ChatEvent;
use Bot\Event\Event;
use Bot\Event\PersonChatEvent;
use Bot\Extensions\AutoListener;
use Bot\Extensions\SyncInfoExtension;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\MiraiApiHttp\MiraiApiHttpProvider;
use Bot\Provider\Provider;
use Console\ErrorFormat;
use Exceptions\NeedAuthException;
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
class Bot extends Model
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
     * @throws NeedAuthException
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

    /** @var Bot $instance */
    public static $instance;
    public $startAt;
    /** @var Application $command */
    protected $command;
    /** @var \Bot\Command[] $commands */
    protected $commands;
    /** @var AutoListener[] $listeners */
    protected $listeners=[];
    /** @var BotPlugin[] $plugins */
    protected $plugins=[];
    /** @var AutoLoader $autoLoader */
    protected $autoLoader;
    /** @var Provider $provider */
    public $provider;

    public function run()
    {
        Logger::info('构造');
        self::$instance = $this;
        $this->startAt = time();
        $this->receiveQueue = new SplQueue();
        $this->sendQueue = new SplQueue();
        $this->autoLoader = new AutoLoader();
        Logger::info('载入命令解析器');
        $this->loadCommand();

        Logger::info('监听事件');
        $this->registerListener();

        Logger::info('载入时钟');
        go(function () {
            $this->plugin();
        });
        go(function () {
            $this->ticker();
        });
        Logger::info('载入应用');
        switch ($this->enable){
            case 1:
                $this->provider=new MiraiApiHttpProvider($this);
                break;
            case 2:
                $this->provider=new IIROSEProvider($this);
        }
    }

    private function plugin()
    {
        while (true) {
            try {
                $plugins_list = BotPlugin::findByBot($this);
                $plugins=[];
                foreach ($plugins_list as $plugin) {
                    $plugins[$plugin->id]=$plugin;
                }
                foreach ($plugins as $plugin) {
                    if (!isset($this->plugins[$plugin->id])) {
                        $this->plugins[$plugin->id]=$plugin;
                        $this->plugins[$plugin->id]->loading($this);
                    }
                }
                foreach ($this->plugins as $plugin) {
                    if (!isset($plugins[$plugin->id])) {
                        unset($this->plugins[$plugin->id]);
                    }
                }
                foreach ($this->plugins as $plugin) {
                    $this->plugins[$plugin->id]->check();
                }
            } catch (Throwable $e) {
                ErrorFormat::dump($e);
            }
            \Co::sleep(5);
        }
    }

    private function ticker()
    {
        while (true) {
            foreach ($this->plugins as $plugin) {
                $plugin->tick();
            }
            \Co::sleep(0.1);
        }
    }

    public function getAutoLoader()
    {
        return $this->autoLoader;
    }

    public function getHandler($handleClass)
    {
        return $this->handles[$handleClass];
    }

    private function loadCommand()
    {
        $this->command = new Application('IIROSE-BOT-' . $this->username, '喵呜~');
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
        foreach ($this->commands as $command) {
            $this->command->add($command);
        }
    }

    /**
     * @param $room
     * @throws Throwable
     */
    public function setRoom($room)
    {
        $this->room = $room;
        $this->saveOrFail();
    }


    public function loaded()
    {
        return true;
    }

    public function event($event)
    {
        if(!(new \ReflectionClass($event))->isSubclassOf(Event::class)) {
            ErrorFormat::dump(new \Exception('非event被转递'));
        }
        foreach ($this->listeners as $listener){
            $listener->onEvent($event);
        }
    }

    /**
     * @param AutoListener $shadow
     * @throws \ReflectionException
     */
    public function addListener($shadow)
    {
        if(in_array($shadow,$this->listeners)){
            return;
        }
        $this->listeners[] = $shadow;
    }

    public function onChat(ChatEvent $chatEvent)
    {
        if (substr($chatEvent->getMessage(), 0, 1) == '/') {
            $output = new BufferedOutput();
            try {
                $this->command->run(new InputUtils(substr($chatEvent->getMessage(), 1), $chatEvent), $output);
            } catch (ExitException $e) {
            } catch (Throwable $e) {
                ErrorFormat::dump($e);
            }
            $d = $output->fetch();
            if (strlen($d)) {
                $chatEvent->getSender()->sendMessage($d);
            }
        }
    }

    public function onPersonChat(PersonChatEvent $personChatEvent)
    {
        if (substr($personChatEvent->getMessage(), 0, 1) == '/') {
            $output = new BufferedOutput();
            try {
                Bot::$instance->command->run(new InputUtils(substr($personChatEvent->getMessage(), 1), $personChatEvent), $output);
            } catch (ExitException $e) {
            } catch (Throwable $e) {
                ErrorFormat::dump($e);
            }
            $d = $output->fetch();
            if (strlen($d)) {
                $personChatEvent->getSender()->sendMessage($d);
            }
        }
    }
}
