<?php


namespace Bot\Extensions;


use Bot\Event\CommandEvent;
use Bot\Event\Event;

trait ManualListener
{
    use AutoListener;
    private $all;
    protected function listener($event,$callable){
        if(!isset($this->all[$event])){
            $this->all[$event]=[];
        }
        if(in_array($callable,$this->all[$event])){
            return;
        }
        $this->all[$event][]=$callable;
        $this->registerListener();
    }
    public function event(Event $event)
    {
        foreach ($this->all as $k=>$v) {
            $class = new \ReflectionClass($event);
            if ($class->isSubclassOf($k)
                || $class->getName() == $k) {
                foreach ($v as $vv) {
                    call_user_func($vv,$event);
                }
            }
        }
    }
}