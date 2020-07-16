<?php


namespace Bot\Packets;


use Models\Bot;

class LikePacket implements \Bot\Packet
{
    public $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }
    public function compile()
    {
        return '+*'.$this->user_id;
    }
}