<?php


namespace Bot\Packets;

class BoardCastPacket implements \Bot\Packet
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
