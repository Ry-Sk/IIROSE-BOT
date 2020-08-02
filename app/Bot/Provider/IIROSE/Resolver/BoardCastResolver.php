<?php

namespace Bot\Provider\IIROSE\Resolver;

use Bot\Provider\IIROSE\Event\BoardCastEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Resolver;
use Models\Bot;

class BoardCastResolver implements Resolver
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($firstChar == '=' && $count == 6) {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = IIROSEProvider::decode(explode('>', $message));
        return new BoardCastEvent(
            $a[1],
            $a[2],
            substr($a[0], 1),
            $a[3],
            $a[5]
        );
    }
}
