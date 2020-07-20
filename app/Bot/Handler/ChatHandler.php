<?php
namespace Bot\Handler;

use Bot\Event\ChatEvent;
use Bot\Handler;
use Models\Bot;

class ChatHandler implements Handler
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count == 11
            && !($explode[0]<Bot::$instance->startAt)
            && ($explode[2]!=Bot::$instance->username)) {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = Bot::decode(explode('>', $message));
        return new ChatEvent(
            $a[3],
            $a[4],
            $a[10],
            $a[8],
            $a[2],
            $a[9],
            $a[1],
            $a[0]
        );
    }
}
