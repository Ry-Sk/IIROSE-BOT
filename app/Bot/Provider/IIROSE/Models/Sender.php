<?php

namespace Bot\Provider\IIROSE\Models;

use Bot\Packets\PersonChatPacket;
use Bot\Provider\IIROSE\IIROSEProvider;

class Sender extends \Bot\Models\Sender
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

    public function sendMessage($message,$color=null)
    {
        $this->sendRawMessage('\\\\\\~'.$message,$color);
    }

    public function sendRawMessage($message,$color=null)
    {
        IIROSEProvider::$instance->packet(new PersonChatPacket($this->user_id,$message,$color?:$this->color));
    }
}