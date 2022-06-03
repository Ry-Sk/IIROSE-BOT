<?php

namespace Bot\Provider\IIROSE\Resolver;

use Bot\Provider\IIROSE\Event\JoinEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Resolver;
use Models\Bot;

class JoinResolver implements Resolver
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($firstChar == '"' && $count == 12
            && $explode[3]=='\'1'
            && substr($explode[0],1)>Bot::$instance->startAt
            && $explode[2]!=Bot::$instance->username) {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = IIROSEProvider::decode(explode('>', $message));
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
