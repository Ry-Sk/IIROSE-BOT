<?php
namespace Bot\Handler;

use Bot\Event\InfoEvent;
use Bot\Event\JoinEvent;
use Bot\Event\UserInfoEvent;
use Bot\Handler;
use Models\Bot;

class InfoHandler implements Handler
{
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count == 23) {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = Bot::decode(explode('>', $message));
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
