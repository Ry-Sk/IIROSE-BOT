<?php


namespace Plugin\AutoLike;

use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Bot\Provider\IIROSE\Event\LikeEvent;
use Bot\Provider\IIROSE\Event\UnlikeEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Packets\LikePacket;
use Bot\Provider\IIROSE\Packets\UnlikePacket;

class AutoLike extends PhpPlugin
{
    public function onLike(LikeEvent $event)
    {
        $user_id=IIROSEProvider::$instance->getUserId($event->user_name);
        IIROSEProvider::$instance->packet(new LikePacket($user_id,'谢谢'));
    }
    public function onUnlike(UnlikeEvent $event)
    {
        $user_id=IIROSEProvider::$instance->getUserId($event->user_name);
        IIROSEProvider::$instance->packet(new UnlikePacket($user_id, '就是这样'));
    }
}
