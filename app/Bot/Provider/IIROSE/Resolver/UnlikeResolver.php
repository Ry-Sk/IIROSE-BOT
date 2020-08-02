<?php

namespace Bot\Provider\IIROSE\Resolver;

use Bot\Provider\IIROSE\Event\UnlikeEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Resolver;
use Models\Bot;

class UnlikeResolver implements Resolver
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count == 7
            && substr($message, 0, 2)=='@*'
            && substr($explode[3], 0, 2)=='\'h') {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = IIROSEProvider::decode(explode('>', substr($message, 2)));
        return new UnlikeEvent(
            $a[6],
            $a[0],
            $a[3],
            $a[1],
            $a[5],
            substr($a[3], 2)
        );
    }
}
