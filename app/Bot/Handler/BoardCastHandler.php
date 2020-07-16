<?php
namespace Bot\Handler;

use Bot\Event\BoardCastEvent;
use Bot\Event\ChatEvent;
use Bot\Handler;
use Models\Bot;

class BoardCastHandler implements Handler
{

    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($firstChar == '=' && $count == 6) {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = Bot::decode(explode('>',$message));
        return new BoardCastEvent(
            $a[1],
            $a[2],
            substr($a[0], 1),
            $a[3],
            $a[5]
        );
    }
}