<?php


namespace Bot\Packets;

use Models\Bot;

class InfoPacket implements \Bot\Packet
{
    public $username;

    public function __construct($username)
    {
        $this->username = $username;
    }
    public function getUsername()
    {
        return $this->username;
    }

    public function compile()
    {
        return '++'.Bot::encode($this->username);
    }
}
