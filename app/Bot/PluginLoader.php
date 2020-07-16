<?php


namespace Bot;


use Models\Bot;

class PluginLoader implements Listenerable
{
    /** @var Bot $bot */
    protected $bot;
    protected $configure;
    protected $slug;
    protected $load=false;
    public function __construct($bot, $configure,$slug)
    {
        $this->bot=$bot;
        $this->configure=json_decode($configure);
        $this->slug=$slug;
    }
    public function load(){
        $this->load=true;
    }
    public function unload(){
        $this->load=false;
    }
    public function reload($configure){
        $this->unload();
        $this->configure=$configure;
        $this->load();
    }

    public function loaded()
    {
        return $this->load;
    }
}