<?php
namespace Bot\Handler;

use Bot\Event\InfoEvent;
use Bot\Event\JoinEvent;
use Bot\Event\NoUserEvent;
use Bot\Event\UserInfoEvent;
use Bot\Handler;

class NoUserHandler implements Handler
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($message == '+') {
            return true;
        }
    }

    public function pharse($message)
    {
        return new NoUserEvent();
    }
}
