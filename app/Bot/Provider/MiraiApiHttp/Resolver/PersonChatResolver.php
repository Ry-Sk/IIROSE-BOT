<?php

namespace Bot\Provider\MiraiApiHttp\Resolver;

use Bot\Provider\IIROSE\Event\PersonChatEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Resolver;
use Models\Bot;

class PersonChatResolver implements Resolver
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count == 10) {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = IIROSEProvider::decode(explode('>', $message));
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
