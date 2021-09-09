<?php

namespace Bot\Provider\IIROSE\Resolver;

use Bot\Provider\IIROSE\Event\NoUserEvent;
use Bot\Provider\IIROSE\Resolver;

class NoUserResolver implements Resolver
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($message == '+') {
            return true;
        }
    }

    public function pharse($message)
    {
        return new NoUserEvent();
    }
}
