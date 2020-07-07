<?php


namespace Plugin\Assert;

use Bot\Event\ChatEvent;
use Bot\Packets\ChatPacket;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;

class Assert extends PhpPlugin
{
    public function onChat(ChatEvent $event)
    {
        if ($event->message=='表白'.$this->bot->username) {
            $this->bot->packet(new ChatPacket(' [*'.$event->user_name.'*] 谢谢'));
        }
    }
}
