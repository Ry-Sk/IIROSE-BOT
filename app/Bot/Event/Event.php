<?php


namespace Bot\Event;


use Models\Bot;

abstract class Event
{
    public function push(){
        Bot::$instance->event($this);
    }
}