<?php


namespace Bot;

class Listener
{
    /** @var Listenerable $method */
    public $plugin;
    /** @var callable $method */
    public $method;
    public function __construct($plugin, $method)
    {
        $this->plugin=$plugin;
        $this->method=$method;
    }
}
