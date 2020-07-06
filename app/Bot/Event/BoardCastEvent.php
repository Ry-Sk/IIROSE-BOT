<?php


namespace Bot\Event;

class BoardCastEvent
{
    public $message;
    public $color;
    public $user_name;
    public $user_color;
    public $user_icon;
    public function __construct(
        $message,
        $color,
        $user_name,
        $user_color,
        $user_icon
    ) {
        $this->message=$message;
        $this->color=$color;
        $this->user_name=$user_name;
        $this->user_color=$user_color;
        $this->user_icon=$user_icon;
    }
}
