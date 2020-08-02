<?php

namespace Bot\Provider\MiraiApiHttp\Event;

use Bot\Provider\MiraiApiHttp\Models\MessageChain;

class ChatEvent extends \Bot\Event\ChatEvent
{
    use MessageChain;
    public $sender;
    public function getMessage()
    {
        return $this->messageGetPlain();
    }

    public function getUserId()
    {
        return $this->getSender()->getUserId();
    }

    public function getUsername()
    {
        return $this->getSender()->getUsername();
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }
}
