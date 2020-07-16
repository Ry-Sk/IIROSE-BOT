<?php


namespace Bot;


use Bot\Packets\ChatPacket;
use Bot\Packets\PersonChatPacket;
use Models\Bot;

class Sender
{
    const ROOM = 'R';
    const PERSON='P';
    private $username;
    private $user_id;
    private $color;
    private $type;
    public function __construct($type,$username,$user_id,$color){
        $this->type=$type;
        $this->username=$username;
        $this->user_id=$user_id;
        $this->color=$color;
    }

    public function getUsername(){
        return $this->username;
    }
    public function getUserId(){
        return $this->user_id;
    }
    public function getColor(){
        return $this->color;
    }
    public function getType(){
        return $this->type;
    }
    public function sendMessage($message,$color=null){
        switch ($this->type){
            case Sender::ROOM:
                Bot::$instance->packet(new ChatPacket($message,$color?:$this->getColor()));
                break;
            case Sender::PERSON:
                Bot::$instance->packet(new PersonChatPacket($this->user_id,$message,$color?:$this->getColor()));
                break;
        }
    }
}
