<?php
namespace Bot\PluginLoader\PhpPlugin;

use Bot\Event\ChatEvent;
use Bot\Handler\ChatHandler;
use Bot\Listener;
use Models\Bot;

class PhpPlugin
{
    /** @var Bot $bot */
    protected $bot;
    protected $config;
    protected $pluginLoader;
    public function __construct($bot,$config,$pluginLoader)
    {
        $this->bot = $bot;
        $this->config = $config;
        $this->pluginLoader = $pluginLoader;
    }
}