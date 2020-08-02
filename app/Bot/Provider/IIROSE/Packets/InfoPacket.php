<?php


namespace Bot\Provider\IIROSE\Packets;

use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Packet;

class InfoPacket implements Packet
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
        return '++'.IIROSEProvider::encode($this->username);
    }
}
