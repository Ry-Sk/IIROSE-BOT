<?php


namespace Plugin\Js;

use Bot\Event\ChatEvent;
use Bot\Event\CommandEvent;
use Bot\Packets\ChatPacket;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Co\System;
use Console\ErrorFormat;

class Js extends PhpPlugin
{
    public function onChat(ChatEvent $event)
    {
        if(substr($event->message,0,9)=='/js:eval '){
            go(function ()use($event){
                ob_start();
                try {
                    $v8=new \V8Js('IB',[],[],false);
                    $v8->setMemoryLimit(1024*1025*5);
                    $v8->setTimeLimit(1000*5);
                    $v8->executeString(substr($event->message,9));
                }catch (\Throwable $e){
                    echo get_class($e);
                    echo $e->getMessage();
                }
                $result=ob_get_contents();
                ob_end_clean();
                $this->bot->packet(new ChatPacket($result,$event->color));
            });
        }
    }
}
