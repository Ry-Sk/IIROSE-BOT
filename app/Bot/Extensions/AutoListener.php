<?php


namespace Bot\Extensions;

use Bot\Event\CommandEvent;
use Bot\Provider\IIROSE\Event\Event;
use Models\Bot;
use ReflectionClass;

trait AutoListener
{
    private $shadows=[];
    public function registerListener($shadow=null){
        if($shadow == null){
            $shadow=$this;
        }
        if(in_array($shadow,$this->shadows)){
            return;
        }
        $this->shadows[]=$shadow;
        Bot::$instance->addListener($this);
    }
    public function onEvent($event)
    {
        foreach ($this->shadows as $shadow) {
            $methods = (new ReflectionClass($shadow))->getMethods();
            foreach ($methods as $method) {
                $parms = $method->getParameters();
                if (count($parms) == 1) {
                    $class = $parms[0]->getClass();
                    if ($class) {
                        if ((new ReflectionClass($event))->isSubclassOf($class)
                            || $class->getName() == get_class($event)) {
                            $method->invoke($shadow, $event);
                        }
                    }
                }
            }
        }
    }
    abstract function loaded();
}
