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
                $method=$listener->method;
                if($listener->plugin->loaded()) {
                    $listener->plugin->$method($event);
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
}