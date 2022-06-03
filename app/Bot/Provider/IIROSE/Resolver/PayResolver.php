<?php

namespace Bot\Provider\IIROSE\Resolver;

use Bot\Provider\IIROSE\Event\PayEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Resolver;

class PayResolver implements Resolver
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count==7
            && substr($message, 0, 2) == '@*'
            && substr($explode[3], 0, 2) == '\'$') {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = IIROSEProvider::decode(explode('>', $message));
        $ma=explode(' ', $a[3]);
        return new PayEvent(
            $a[6],
            substr($a[0], 1),
            $a[1],
            $a[5],
            substr($ma[0], 2),
            isset($ma[2])?substr($a[3], strlen($ma[0]+1)):null
        );
    }
}
