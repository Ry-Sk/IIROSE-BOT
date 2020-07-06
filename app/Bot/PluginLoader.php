<?php


namespace Bot;


use Models\Bot;

class PluginLoader
{
    /** @var Bot $bot */
    protected $bot;
    protected $configure;
    protected $slug;
    public function __construct($bot, $configure,$slug)
    {
        $this->bot=$bot;
        $this->configure=json_decode($configure);
        $this->slug=$slug;
    }
    public function load(){}
}