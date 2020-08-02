<?php


namespace Bot\Packets;

use Bot\Provider\IIROSE\Packet;

class PersonChatPacket implements Packet
{
    public $message;
    public $color;
    public $user_id;

    public function __construct($user_id, $message, $color='6ebadb')
    {
        $this->user_id = $user_id;
        $this->message = $message;
        $this->color = $color;
    }
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
    public function setColor(string $color): PersonChatPacket
    {
        $this->color = $color;
        return $this;
    }
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }
    public function compile()
    {
        return json_encode(
            [
                'g' => $this->user_id,
                'm' => $this->message,
                'mc' => $this->color,
                'i' => uniqid($this->message)
            ]
        );
    }
}
