<?php
namespace Models;

use Bot\AutoListener;
use Bot\AutoLoader;
use Bot\Connection;
use Bot\Event\ChatEvent;
use Bot\Exception\NetworkException;
use Bot\Handle;
use Bot\Handler;
use Bot\Listenerable;
use Bot\Packet;
use Bot\Packets\PingPacket;
use Console\Commands\Command;
use Console\ErrorFormat;
use Exceptions\NeedAuthException;
use File\File;
use Logger\Logger;
use Model\Models\Model;
use SplQueue;

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
    public static function auth($token){
        /** @var Bot $bot */
        $bot=self::where('token','=',$token)->first();
        return $bot;
    }
    /**
     * @param $token
     * @return Bot
     */
    public static function authOrFail($token){
        $bot=self::auth($token);
        if($bot){
            return $bot;
        }else{
            throw new NeedAuthException();
        }
    }
    use AutoListener;
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
    /** @var BotPlugin[] $plugins */
    protected $plugins;
    /** @var AutoLoader $autoLoader */
    protected $autoLoader;
    public function run()
    {
        Logger::info('构造');
        self::$instance=$this;
        $this->startAt=time();
        $this->receiveQueue=new SplQueue();
        $this->sendQueue=new SplQueue();
        $this->autoLoader=new AutoLoader();

        Logger::info('载入加载器');
        $files=File::scan_dir_files(ROOT.'/app/Bot/Handler/',false);
        foreach ($files as $file){
            $handler_class='\\Bot\\Handler\\'.substr($file,0,strlen($file)-4);
            /** @var \ReflectionClass $handler_class */
            $handler = new $handler_class();
            $this->handles[substr($handler_class,1)]=new Handle($handler);
        }

        Logger::info('监听事件');
        $this->registerListeners();

        Logger::info('载入插件');
        $this->plugins=BotPlugin::findByBot($this);
        foreach ($this->plugins as $plugin){
            $plugin->loading($this);
        }
        go(function (){
            $this->protect();
        });
        go(function (){
            $this->receive();
        });
        go(function (){
            $this->send();
        });
        go(function (){
            $this->timer();
        });
    }
    private function login()
    {
        $this->connect=new Connection($this->username,$this->password);
        try {
            $this->connect->login($this->room, function ($packet) {
                $this->receiveQueue->push($packet);
            });
        } catch (NetworkException $e) {
            ErrorFormat::dump($e);
        }
    }
    private function receive(){
        while (true){
            if($this->receiveQueue->count()) {
                $this->solve($this->receiveQueue->pop());
            }
            \Co::sleep(0.1);
        }
    }
    private function send(){
        while (true){
            if($this->sendQueue->count()) {
                $data=$this->sendQueue->pop();
                while (true){
                    try {
                        if($this->connect->send($data)){
                            break;
                        }
                    }catch (\Throwable $e){}
                    \Co::sleep(5);
                }
            }
            \Co::sleep(0.1);
        }
    }
    private function protect(){
        $this->login();
        while (true){
            \Co::sleep(0.1);
            if(@$this->connect->alive()) {
                continue;
            }
            Logger::warn('连接丢失，尝试重连接');
            if($this->connect){
                $this->connect->close();
            }
            $this->login();
        }
    }
    private function timer(){
        swoole_timer_tick(
            5000, function () {
            $this->packet(new PingPacket());
        }
        );
    }
    private function solve($message){
        $s = @gzdecode(substr($message, 1)) ?: $message;
        $dump = explode('<', $s);
        foreach ($dump as $p) {
            $this->parse($p);
        }
    }
    private function parse(string $message)
    {
        $firstChar = substr($message, 0, 1);
        $explode = explode('>',$message);
        $count=count($explode);
        foreach ($this->handles as $handle){
            $handle->onPacket($message,$firstChar,$count,$explode);
        }
    }
    public function packet(Packet $packet)
    {
        $this->sendQueue->push($packet->compile());
    }
    public function getAutoLoader(){
        return $this->autoLoader;
    }
    public function getHandler($handleClass){
        return $this->handles[$handleClass];
    }

    public function loaded()
    {
        return true;
    }
}