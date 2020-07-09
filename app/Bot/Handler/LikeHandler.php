<?php
namespace Bot\Handler;

use Bot\Event\ChatEvent;
use Bot\Event\LikeEvent;
use Bot\Handler;
use Models\Bot;

class LikeHandler implements Handler
{
    // @*K空白B>006smjLKly1g3vxrifgb9j305k05kaa7>2>'*>>1594276766>8e8da8
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count == 7
            && substr($message,0,2)=='@*'
            && substr($explode[3],0,2)=='\'*') {
            return true;
        }
    }
    public function pharse($message)
    {
        $a = Bot::decode(explode('>',substr($message,2)));
        return new LikeEvent(
            $a[6],
            $a[0],
            $a[3],
            $a[1],
            $a[5]
        );
    }
}