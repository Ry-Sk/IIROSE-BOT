<?php


namespace Bot\Provider\IIROSE\Packets;

use Bot\Provider\IIROSE\Packet;

class AdminForbiddenClean implements Packet
{
    public $user_id;


    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function compile()
    {
        return  '!h3'.json_encode(['1','0_'.addslashes($this->user_id)]);
    }
}
