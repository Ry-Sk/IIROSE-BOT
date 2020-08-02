<?php


namespace Plugin\AutoPayBack;

use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Bot\Provider\IIROSE\Event\PayEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Packets\PayPacket;
use Models\Bot;

class AutoPayBack extends PhpPlugin
{
    public function onPay(PayEvent $event)
    {
        IIROSEProvider::$instance->packet(new PayPacket($this->bot->getUserId($event->user_name), $event->count));
    }
}
