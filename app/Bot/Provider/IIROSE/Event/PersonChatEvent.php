<?php

namespace Bot\Provider\IIROSE\Event;

use Bot\Provider\IIROSE\Models\Sender;

class PersonChatEvent extends \Bot\Event\PersonChatEvent
{
    public $message;
    public $color;
    public $user_id;
    public $id;
    public $user_name;
    public $user_icon;

    public function __construct(
        $message,
        $color,
        $id,
        $user_id,
        $user_name,
        $user_icon
    ) {
        $this->message = $message;
        $this->color = $color;
        $this->id = $id;
        $this->user_id = $user_id;
        $this->user_name = $user_name;
        $this->user_icon = $user_icon;
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
        return new Sender($this->user_name,$this->user_id,$this->color);
    }
}
