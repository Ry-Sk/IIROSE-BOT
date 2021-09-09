<?php


namespace Bot\Provider\IIROSE\Packets;

use Bot\Provider\IIROSE\Packet;

class BoardCastPacket implements Packet
{
    public $message;
    public $color;

    public function __construct($message, $color = '6ebadb')
    {
        $this->message = $message;
        $this->color = $color;
    }
    public function getMessage()
    {
        return $this->message;
    }
    public function getColor()
    {
        return $this->color;
    }
    public function compile()
    {
        return '~' . json_encode(
            [
                't' => $this->message,
                'c' => $this->color
            ]
        );
    }
}
