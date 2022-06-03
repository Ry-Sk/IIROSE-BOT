<?php

namespace Bot\Provider\IIROSE\Resolver;

use Bot\Provider\IIROSE\Event\UserInfoEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Resolver;
use Models\Bot;

class UserInfoResolver implements Resolver
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($firstChar=='%' && $count == 12 && $explode[5] == 'n') {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = IIROSEProvider::decode(explode('>', $message));
        return new UserInfoEvent(
            $a[8],
            $a[2],
            $a[4],
            $a[3],
            $a[1]
        );
    }
}
