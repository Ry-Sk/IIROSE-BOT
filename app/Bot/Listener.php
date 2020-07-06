<?php


namespace Bot;


class Listener
{
    public $plugin;
    public $method;
    public function __construct($plugin,$method)
    {
        $this->plugin=$plugin;
        $this->method=$method;
    }
}