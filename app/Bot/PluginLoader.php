<?php


namespace Bot;

use Models\Bot;

class PluginLoader
{
    /** @var Bot $bot */
    protected $bot;
    protected $configure;
    protected $slug;
    protected $botPlugin;
    protected $load=false;
    public function __construct($bot, $botPlugin, $slug)
    {
        $this->bot=$bot;
        $this->botPlugin=$botPlugin;
        $this->configure=json_decode($botPlugin->configure, true);
        $this->slug=$slug;
    }
    public function load()
    {
        $this->load=true;
    }
    public function unload()
    {
        $this->load=false;
    }
    public function reload($configure)
    {
        $this->unload();
        $this->configure=json_decode($configure, true);
        $this->load();
    }

    public function tick()
    {
    }
}
