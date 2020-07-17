<?php


namespace Plugin\Assert;

use Bot\Event\PayEvent;
use Bot\Packets\LikePacket;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;

class Assert extends PhpPlugin
{
    public function onPay(PayEvent $event)
    {
        $user_id=$this->bot->getUserId($event->user_name);
        $this->bot->packet(new LikePacket($user_id));
    }
}
