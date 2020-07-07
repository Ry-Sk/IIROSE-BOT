<?php
namespace Bot;


class Handle
{
    /** @var Handler $handler */
    private $handler;
    /** @var Listener[] $listeners */
    private $listeners=[];
    public function __construct($handler)
    {
        $this->handler=$handler;
    }
    public function onPacket($message,$firstChar,$count,$explode){
        if($this->handler->isPacket($message,$firstChar,$count,$explode)){
            $event=$this->handler->pharse($message);
            foreach ($this->listeners as $key=>$listener) {
                if($listener->plugin->loaded()) {
                    call_user_func($listener->method,$event);
                }else{
                    unset($this->listeners[$key]);
                }
            }
        }
    }
    public function addListener(Listener $listener){
        if(in_array($listener,$this->listeners)){
            return;
        }
        $this->listeners[]=$listener;
    }
    public function onEvent($event){
        foreach ($this->listeners as $key=>$listener) {
            if($listener->plugin->loaded()) {
                call_user_func($listener->method,$event);
            }else{
                unset($this->listeners[$key]);
            }
        }
    }
}