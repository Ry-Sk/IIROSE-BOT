<?php


namespace Plugin\Assert;

use Bot\Event\ChatEvent;
use Bot\Event\CommandEvent;
use Bot\Event\InfoEvent;
use Bot\Event\LikeEvent;
use Bot\Event\UserInfoEvent;
use Bot\Packets\ChatPacket;
use Bot\Packets\LikePacket;
use Bot\Packets\SourcePacket;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Models\Bot;

class Assert extends PhpPlugin
{
    public function onLike(LikeEvent $event)
    {
        $user_id=$this->bot->getUserId($event->user_name);
        $this->bot->packet(new LikePacket($user_id));
    }
}
