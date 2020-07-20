<?php


namespace Bot\Packets;

use Bot\Packet;

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
