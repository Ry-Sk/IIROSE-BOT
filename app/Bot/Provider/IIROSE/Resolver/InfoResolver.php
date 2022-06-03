<?php

namespace Bot\Provider\IIROSE\Resolver;

use Bot\Provider\IIROSE\Event\InfoEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Resolver;
use Models\Bot;

class InfoResolver implements Resolver
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($firstChar == '+') {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = IIROSEProvider::decode(explode('>', $message));
        $event = new InfoEvent();
        $event->user_id = $a[3];
        $event->first_name = $a[5];
        $event->second_name = $a[6];
        $event->birth_day = $a[7];
        $event->address = $a[8];
        $event->website = $a[9];
        $event->hobby = $a[10];
        $event->friends = $a[11];
        $event->info = $a[12];
        $event->info_background = $a[13];
        $event->last_active = $a[16];
        $event->view = $a[17];
        $event->fans = $a[22];
        $event->likes = $a[21];
        $event->count = $a[21];
        return $event;
    }
}
