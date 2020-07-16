<?php


namespace Plugin\AutoPayBack;

use Bot\Event\ChatEvent;
use Bot\Event\CommandEvent;
use Bot\Event\InfoEvent;
use Bot\Event\LikeEvent;
use Bot\Event\PayEvent;
use Bot\Event\UnlikeEvent;
use Bot\Event\UserInfoEvent;
use Bot\Packets\ChatPacket;
use Bot\Packets\LikePacket;
use Bot\Packets\PayPacket;
use Bot\Packets\SourcePacket;
use Bot\Packets\UnlikePacket;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Models\Bot;

class AutoPayBack extends PhpPlugin
{
    public function onPay(PayEvent $event)
    {
        $this->bot->packet(new PayPacket($this->bot->getUserId($event->user_name),$event->count));
    }
}
