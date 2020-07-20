<?php


namespace Bot\Packets;

class ChatPacket implements \Bot\Packet
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
                'i' => uniqid($this->message)
            ]
        );
    }
}
