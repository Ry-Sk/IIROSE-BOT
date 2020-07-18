<?php


namespace Bot\Packets;


use Models\Bot;

class AdminForbiddenClean implements \Bot\Packet
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