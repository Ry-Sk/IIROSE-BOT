<?php
namespace Bot\Handler;

use Bot\Event\PersonChatEvent;
use Bot\Handler;
use Models\Bot;

class PersonChatHandler implements Handler
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count == 10) {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = Bot::decode(explode('>', $message));
        return new PersonChatEvent(
            $a[3],
            $a[4],
            $a[9],
            substr($a[0], 1),
            $a[1],
            $a[2]
        );
    }
}
