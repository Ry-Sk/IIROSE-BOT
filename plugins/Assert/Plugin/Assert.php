<?php


namespace Plugin\Assert;

use Bot\Event\ChatEvent;
use Bot\Event\PersonChatEvent;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;

class Assert extends PhpPlugin
{
    public function onChat(PersonChatEvent $event)
    {
        var_dump($event->getMessage());
    }
}
