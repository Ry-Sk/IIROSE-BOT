<?php


namespace Plugin\Count;

use Bot\Event\ChatEvent;
use Bot\Packets\ChatPacket;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Console\ErrorFormat;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class Count extends PhpPlugin
{
    public function __construct($bot, $config, $pluginLoader)
    {
        parent::__construct($bot, $config, $pluginLoader);
        DB::
    }

    public function onChat(ChatEvent $event)
    {
    }
}
