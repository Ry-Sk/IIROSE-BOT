<?php


namespace Bot\Event;

class PayEvent
{
    public $color;
    public $user_name;
    public $user_icon;
    public $time;
    public $count;

    public function __construct(
        $color,
        $user_name,
        $user_icon,
        $time,
        $count
    ) {
        $this->color = $color;
        $this->user_name = $user_name;
        $this->user_icon = $user_icon;
        $this->time = $time;
        $this->count = $count;
    }
}
