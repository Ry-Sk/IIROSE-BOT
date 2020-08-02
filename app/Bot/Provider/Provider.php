<?php
namespace Bot\Provider;

abstract class Provider
{
    public abstract function getGroupList();
    public abstract function sendRoomChat($room_id,$message);
    public abstract function sendPersonChat($user_id,$message);
}