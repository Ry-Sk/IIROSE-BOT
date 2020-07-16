<?php


namespace Bot\Event;

class UserInfoEvent
{
    public $user_id;
    public $username;
    public $room_id;
    public $color;
    public $online;
    public $time;
    public function __construct($user_id,$username,$room_id,$color,$online){
        $this->user_id=$user_id;
        $this->username=$username;
        $this->room_id=$room_id;
        $this->color=$color;
        $this->online=$online;
        $this->time=time();
    }
}
