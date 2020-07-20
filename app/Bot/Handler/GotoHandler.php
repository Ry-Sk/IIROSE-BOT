<?php
namespace Bot\Handler;

use Bot\Event\GotoEvent;
use Bot\Handler;
use Models\Bot;

class GotoHandler implements Handler
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count == 12
            && substr($explode[3], 0, 1) == '\''
            && $explode[3] != '\'1'
            && $explode[3] != '\'3') {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = Bot::decode(explode('>', $message));
        return new GotoEvent(
            $a[5],
            $a[8],
            $a[2],
            $a[9],
            $a[1],
            substr($a[11], 1),
            $a[0]
        );
    }
}
