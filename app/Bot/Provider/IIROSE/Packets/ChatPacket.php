<?php


namespace Bot\Provider\IIROSE\Packets;

use Bot\Provider\IIROSE\Packet;

class ChatPacket implements Packet
{
    public $message;
    public $color;
    public function __construct($message, $color = '6ebadb')
    {
        $this->message=$message;
        $this->color=$color;
    }
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
    public function setColor(string $color)
    {
        $this->color = $color;
        return $this;
    }

    public function compile()
    {
        return json_encode(
            [
                'm' => $this->message,
                'mc' => $this->color,
                'i' => mt_rand(100000000000,999999999999)
            ]
        );
    }
}
