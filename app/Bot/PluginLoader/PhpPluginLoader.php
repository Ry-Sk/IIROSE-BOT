<?php
namespace Bot\PluginLoader;

use Bot\Listener;
use Bot\PluginLoader;

class PhpPluginLoader extends PluginLoader
{
    public $plugin;
    public function load()
    {
        $this->bot->getAutoLoader()->add('Plugin\\'.$this->slug,ROOT.'/plugins/PHP/'.$this->slug.'/Plugin');
        $plugin_class = '\\Plugin\\'.$this->slug.'\\'. $this->slug;
        $this->plugin = new $plugin_class($this->bot, $this->configure,$this);
        $this->registerListeners();
    }
    public function loaded(){
        return true;
    }

    private function registerListeners()
    {
        $methods=(new \ReflectionClass($this->plugin))->getMethods();
        foreach($methods as $method){
            $parms=$method->getParameters();
            if(count($parms)==1){
                $class=$parms[0]->getClass();
                if($class){
                    $className=$class->getName();
                    if(substr($className,0,10)=='Bot\\Event\\'){
                        $slug=substr($className,10,strlen($className)-15);
                        //var_dump('Bot\Handler\ChatHandler');
                        $className='Bot\\Handler\\'.$slug.'Handler';
                        $this->bot->getHandler($className)->addListener(
                            new Listener($this,'@'.$method->getName())
                        );
                    }
                }
            }
        }
    }
    public function __call($name, $arguments)
    {
        if(substr($name,0,1)=='@'){
            $method=substr($name,1);
            call_user_func_array([$this->plugin,$method],$arguments);
        }
    }
}