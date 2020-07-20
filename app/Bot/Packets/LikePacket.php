<?php


namespace Bot\Packets;

use Models\Bot;

class LikePacket implements \Bot\Packet
{
    public $user_id;
    public $message;

    public function __construct($user_id, $message)
    {
        $this->user_id = $user_id;
        $this->message = $message;
    }
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
    public function compile()
    {
        return '+*'.$this->user_id.' '.Bot::encode($this->message);
    }
}
