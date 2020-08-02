<?php


namespace Plugin\Assert;

use Bot\Event\ChatEvent;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;

class Assert extends PhpPlugin
{
    public function onChat(ChatEvent $event)
    {
        var_dump($event->getMessage());
        if(sub) {
            $event->getSender()->sendMessage('谢谢');
        }
    }
}
