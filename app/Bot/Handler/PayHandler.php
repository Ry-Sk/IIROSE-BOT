<?php
namespace Bot\Handler;

use Bot\Event\InfoEvent;
use Bot\Event\JoinEvent;
use Bot\Event\NoUserEvent;
use Bot\Event\UserInfoEvent;
use Bot\Handler;

class PayHandler implements Handler
{

    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count==7
            && substr($message, 0, 2) == '@*'
            && substr($explode[3], 0, 2) == '\'$') {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = explode('>',$message);
        return new PayEvent(
            $a[6],
            substr($a[0], 2),
            $a[1],
            $a[5],
            substr($a[3], 2)
        );
    }
}