<?php

namespace Bot\Event;

class LikeEvent
{
    public $color;
    public $user_name;
    public $user_tag;
    public $user_icon;
    public $time;

    public function __construct(
        $color,
        $user_name,
        $user_tag,
        $user_icon,
        $time
    ) {
        $this->color = $color;
        $this->user_name = $user_name;
        $this->user_tag = $user_tag;
        $this->user_icon = $user_icon;
        $this->time = $time;
    }
}
