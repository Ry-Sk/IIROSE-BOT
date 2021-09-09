<?php

namespace Bot\Provider\IIROSE\Event;

use Bot\Provider\IIROSE\Models\GroupSender;

class ChatEvent extends \Bot\Event\ChatEvent
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
    )
    {
        $this->message = $message;
        $this->color = $color;
        $this->id = $id;
        $this->user_id = $user_id;
        $this->user_name = $user_name;
        $this->user_tag = $user_tag;
        $this->user_icon = $user_icon;
        $this->time = $time;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getUsername()
    {
        return $this->user_name;
    }
    public function getSender()
    {
        return new GroupSender($this->user_name,$this->user_id,$this->color);
    }
}
