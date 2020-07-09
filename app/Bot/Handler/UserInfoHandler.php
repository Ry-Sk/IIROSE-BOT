<?php
namespace Bot\Handler;

use Bot\Event\JoinEvent;
use Bot\Event\UserInfoEvent;
use Bot\Handler;
use Models\Bot;

class UserInfoHandler implements Handler
{

    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count == 12 && $explode[5] == 'n') {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = Bot::decode(explode('>',$message));
        return new UserInfoEvent(
            $a[8],
            $a[2],
            $a[4],
            $a[3],
            $a[1]
        );
    }
}