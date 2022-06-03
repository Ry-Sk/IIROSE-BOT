<?php

namespace Bot\Provider\IIROSE\Resolver;

use Bot\Provider\IIROSE\Event\LikeEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Resolver;
use Models\Bot;

class LikeResolver implements Resolver
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count == 7
            && substr($message, 0, 2)=='@*'
            && substr($explode[3], 0, 2)=='\'*') {
            return true;
        }
    }
    public function pharse($message)
    {
        $a = IIROSEProvider::decode(explode('>', substr($message, 1)));
        return new LikeEvent(
            $a[6],
            $a[0],
            $a[3],
            $a[1],
            $a[5]
        );
    }
}
