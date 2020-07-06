<?php
namespace Bot\Handler;

use Bot\Event\JoinEvent;
use Bot\Handler;

class JoinHandler implements Handler
{

    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count == 12 && $explode[3]=='\'1') {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = explode('>',$message);
        return new JoinEvent(
            $a[5],
            $a[8],
            $a[2],
            $a[9],
            $a[1],
            $a[0]
        );
    }
}