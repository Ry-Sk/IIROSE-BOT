<?php


namespace Plugin\Welcome;

use Bot\Event\ChatEvent;
use Bot\Event\JoinEvent;
use Bot\Packets\ChatPacket;
use Bot\Packets\LikePacket;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Console\ErrorFormat;
use GuzzleHttp\Client;
use Models\Bot;

class Welcome extends PhpPlugin
{
    public function onJoin(JoinEvent $event)
    {
        $this->bot->packet(new ChatPacket(' [*'.$event->user_name.'*] 欢迎～',$event->color));
        //$this->bot->packet(new LikePacket($event->user_id));
    }
}
