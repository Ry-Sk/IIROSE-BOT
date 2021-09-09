<?php


namespace Plugin\Welcome;

use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Bot\Provider\IIROSE\Event\JoinEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Packets\ChatPacket;

class Welcome extends PhpPlugin
{
    public function onJoin(JoinEvent $event)
    {
        IIROSEProvider::$instance->packet(new ChatPacket(' [*'.$event->user_name.'*] 欢迎回家～', $event->color));
    }
}
