<?php


namespace Bot\Packets;

class PayPacket implements \Bot\Packet
{
    public $message;
    public $count;
    public $user_id;

    public function __construct($user_id, $count, $message=null)
    {
        $this->user_id = $user_id;
        $this->message=$message;
        $this->count=$count;
    }
    public function setUserId(string $user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }
    public function setCount(string $count)
    {
        $this->count = $count;
        return $this;
    }
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
    public function compile()
    {
        return '+$'.json_encode(
            [
                'g' => $this->user_id,
                'c' => $this->count,
                'm' => $this->message
            ]
        );
    }
}
