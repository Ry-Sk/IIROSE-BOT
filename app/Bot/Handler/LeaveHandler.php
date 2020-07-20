<?php
namespace Bot\Handler;

use Bot\Event\LeaveEvent;
use Bot\Handler;
use Models\Bot;

class LeaveHandler implements Handler
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count == 12 && $explode[3]=='\'3') {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = Bot::decode(explode('>', $message));
        return new LeaveEvent(
            $a[5],
            $a[8],
            $a[2],
            $a[9],
            $a[1],
            $a[0]
        );
    }
}
