<?php


namespace Bot\Provider\IIROSE\Event;

class PayEvent extends Event
{
    public $color;
    public $user_name;
    public $user_icon;
    public $time;
    public $count;
    public $message;

    public function __construct(
        $color,
        $user_name,
        $user_icon,
        $time,
        $count,
        $message
    ) {
        $this->color = $color;
        $this->user_name = $user_name;
        $this->user_icon = $user_icon;
        $this->time = $time;
        $this->count = $count;
        $this->message = $message;
    }
}
