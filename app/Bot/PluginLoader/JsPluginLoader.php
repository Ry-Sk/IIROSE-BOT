<?php
namespace Bot\PluginLoader;

use Bot\AutoListener;
use Bot\Listener;
use Bot\Listenerable;
use Bot\PluginLoader;
use Bot\PluginLoader\JsPlugin\JsPackets;
use File\Path;
use Bot\PluginLoader\JsPlugin\JsEvents;
use Models\Bot;
use SplQueue;
use V8Js;

class JsPluginLoader extends PluginLoader implements Listenerable
{
    /** @var V8Js $plugin */
    private $plugin;
    private $basePath;
    /** @var SplQueue $queue */
    protected $receiveQueue;
    public function load()
    {
        $this->load=true;
        $this->basePath=Path::formt_dir(ROOT.'/plugins/'.$this->slug);
        $this->receiveQueue=new SplQueue();
        go(function(){
            $this->plugin=new V8Js('IB');
            $this->plugin->setTimeLimit(5000);
            $this->plugin->setMemoryLimit(1024*1024*512);// 512M
            $this->plugin->setModuleLoader(function ($name){
                var_dump($name);
                return file_get_contents($this->basePath.'Plugin/'.$name.'.js');
            });
            //$this->plugin->setModuleNormaliser();
            $this->plugin->addListener=function($event,$listener){
                $this->addListener($event,$listener);
            };
            $this->plugin->sendPacket=function($packet){
                $this->bot->packet($packet);
            };
            $this->plugin->events=new JsEvents();
            $this->plugin->packets=new JsPackets();
            $this->plugin->configure=$this->configure;
            $this->plugin->executeString(file_get_contents($this->basePath.'Plugin/'.$this->slug.'.js'));
            while (true){
                if(!$this->load){break;}
                if($this->receiveQueue->count()) {
                    $call=$this->receiveQueue->pop();
                    $call();
                }
                \Co::sleep(0.1);
            }
        });
    }
    public function addListener($event,$listener){
        $className=$event;
        if(substr($className,0,10)=='Bot\\Event\\'){
            $slug=substr($className,10,strlen($className)-15);
            $className='Bot\\Handler\\'.$slug.'Handler';
            Bot::$instance->getHandler($className)->addListener(
                new Listener($this,function($event)use($listener){
                    $this->receiveQueue->push(function ()use($event,$listener){
                        $listener($event);
                    });
                })
            );
        }
    }
}