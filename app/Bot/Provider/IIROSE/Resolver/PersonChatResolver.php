<?php

namespace Bot\Provider\IIROSE\Resolver;

use Bot\Provider\IIROSE\Event\PersonChatEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Resolver;
use Models\Bot;

class PersonChatResolver implements Resolver
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if (/* $firstChar == '"' &&  */$count == 11 && strlen($explode[1])==13) {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = IIROSEProvider::decode(explode('>', $message));
        return new PersonChatEvent(
            $a[4],
            $a[5],
            $a[10],
            $a[1],
            $a[2],
            $a[3]
        );
    }
}
