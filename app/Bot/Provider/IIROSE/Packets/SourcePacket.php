<?php


namespace Bot\Provider\IIROSE\Packets;

use Bot\Provider\IIROSE\Packet;

class SourcePacket implements Packet
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
    public function getMessage()
    {
        return $this->message;
    }
    public function compile()
    {
        return $this->message;
    }
}
