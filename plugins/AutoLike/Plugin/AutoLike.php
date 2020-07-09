<?php


namespace Plugin\AutoLike;

use Bot\Event\ChatEvent;
use Bot\Event\CommandEvent;
use Bot\Event\InfoEvent;
use Bot\Event\LikeEvent;
use Bot\Event\UnlikeEvent;
use Bot\Event\UserInfoEvent;
use Bot\Packets\ChatPacket;
use Bot\Packets\LikePacket;
use Bot\Packets\SourcePacket;
use Bot\Packets\UnlikePacket;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Models\Bot;

class AutoLike extends PhpPlugin
{
    public function onLike(LikeEvent $event)
    {
        $user_id=$this->bot->getUserId($event->user_name);
        $this->bot->packet(new LikePacket($user_id));
    }
    public function onUnlike(UnlikeEvent $event)
    {
        $user_id=$this->bot->getUserId($event->user_name);
        $this->bot->packet(new UnlikePacket($user_id,'就是这样'));
    }
}
