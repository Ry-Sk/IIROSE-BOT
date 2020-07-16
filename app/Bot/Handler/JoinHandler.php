<?php
namespace Bot\Handler;

use Bot\Event\JoinEvent;
use Bot\Handler;
use Models\Bot;

class JoinHandler implements Handler
{

    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count == 12
            && $explode[3]=='\'1'
            && $explode[0]>Bot::$instance->startAt
            && $explode[2]!=Bot::$instance->username) {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = Bot::decode(explode('>',$message));
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