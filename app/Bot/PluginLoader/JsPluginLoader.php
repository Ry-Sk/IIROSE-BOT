<?php
namespace Bot\PluginLoader;

use Bot\Extensions\ManualListener;
use Bot\Listener;
use Bot\PluginLoader;
use Bot\PluginLoader\JsPlugin\JsPackets;
use Closure;
use Console\ErrorFormat;
use File\Path;
use Bot\PluginLoader\JsPlugin\JsPhpProvider;
use Models\Bot;
use SplQueue;
use V8Js;

class JsPluginLoader extends PluginLoader
{
    use ManualListener;
    /** @var V8Js $plugin */
    private $plugin;
    private $basePath;
    /** @var SplQueue $queue */
    protected $receiveQueue;
    /** @var Closure $ticker */
    protected $ticker;
    public function load()
    {
        $this->load=true;
        $this->basePath=Path::formt_dir(ROOT.'/plugins/'.$this->slug);
        $this->receiveQueue=new SplQueue();
        $this->ticker=[];
    }
    public function addListener($event, $listener)
    {
        $className=$event;
        $this->listener($event,
            function ($event) use($listener){
                    $this->receiveQueue->push(function () use ($event,$listener) {
                        $listener($event);
                    });
            });
    }
    public function addTicker($ticker)
    {
        $this->ticker[]=function () use($ticker){
                $this->receiveQueue->push(function () use ($ticker) {
                    $ticker();
                });
            };
    }
    public function tick()
    {
        try {
            if (is_null($this->plugin)) {
                $this->plugin = new V8Js('IB');
                $this->plugin->setTimeLimit(5000);
                $this->plugin->setMemoryLimit(1024 * 1024 * 512);// 512M
                $this->plugin->setModuleLoader(function ($name) {
                    var_dump($name);
                    return file_get_contents($this->basePath . 'Plugin/' . $name . '.js');
                });
                //$this->plugin->setModuleNormaliser();
                $this->plugin->addListener = function ($event, $listener) {
                    $this->addListener($event, $listener);
                };
                $this->plugin->addTicker = function ($ticker) {
                    $this->addTicker($ticker);
                };
                $this->plugin->bot = $this->bot;
                $this->plugin->php = new JsPhpProvider();
                $this->plugin->configure = $this->configure;
                $this->plugin->executeString(file_get_contents($this->basePath . 'Plugin/' . $this->slug . '.js'));
            }
            if ($this->receiveQueue->count()) {
                $call = $this->receiveQueue->pop();
                $call();
            }
            \Co::sleep(0.1);
        } catch (\Throwable $e) {
            ErrorFormat::dump($e);
        }
    }

    function loaded()
    {
        return $this->load;
    }
}
