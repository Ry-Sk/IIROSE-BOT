<?php

namespace Bot\Provider\IIROSE\Models;

use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Packets\ChatPacket;
use Models\Bot;

class GroupSender extends \Bot\Models\GroupSender
{
    private $username;
    private $user_id;
    private $color;
    public function __construct($username, $user_id, $color)
    {
        $this->username=$username;
        $this->user_id=$user_id;
        $this->color=$color;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getGroupId()
    {
        return Bot::$instance->room;
    }

    public function sendMessage($message,$color=null)
    {
        $this->sendRawMessage('\\\\\\~'.$message,$color);
    }

    public function sendRawMessage($message,$color=null)
    {
        IIROSEProvider::$instance->packet(new ChatPacket($message,$color?:$this->color));
    }
}