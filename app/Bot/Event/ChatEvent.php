<?php

namespace Bot\Event;

class ChatEvent
{
    public $message;
    public $color;
    public $id;
    public $user_id;
    public $user_name;
    public $user_tag;
    public $user_icon;
    public $time;

    public function __construct(
        $message,
        $color,
        $id,
        $user_id,
        $user_name,
        $user_tag,
        $user_icon,
        $time
    ) {
        $this->message = $message;
        $this->color = $color;
        $this->id = $id;
        $this->user_id = $user_id;
        $this->user_name = $user_name;
        $this->user_tag = $user_tag;
        $this->user_icon = $user_icon;
        $this->time = $time;
    }
}
