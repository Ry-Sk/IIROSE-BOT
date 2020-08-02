<?php

namespace Bot\Provider\IIROSE\Packets;

use Bot\Provider\IIROSE\Packet;

class AdminForbiddenBanPacket implements Packet
{
    const CHAT=1;
    const PLAY=2;
    const ALL=3;
    public $user_name;
    public $time;
    public $describe;
    public $chat;
    public $play;


    public function __construct($user_name, $time, $describe, $chat, $play)
    {
        $this->user_name = $user_name;
        $this->time = $time;
        $this->describe = $describe;
        $this->chat = $chat;
        $this->play = $play;
    }

    public function compile()
    {
        return '!h3'.json_encode([
                '4'.($this->chat*1+$this->play*2),
                $this->user_name,
                $this->time.'s',
                $this->describe
            ]);
    }

    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }
    public function setTime($time)
    {
        $this->time = $time;
    }
    public function setDescribe($describe)
    {
        $this->describe = $describe;
    }
    public function setChat($chat)
    {
        $this->chat = $chat;
    }
    public function setPlay($play): void
    {
        $this->play = $play;
    }
}
