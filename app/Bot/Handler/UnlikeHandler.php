<?php
namespace Bot\Handler;

use Bot\Event\ChatEvent;
use Bot\Event\LikeEvent;
use Bot\Event\UnlikeEvent;
use Bot\Handler;
use Models\Bot;

class UnlikeHandler implements Handler
{
    //+!5e5f1f45689d1 å¤æ³¨
    //@*s&amp;]][[&#092;&#092;&#039;&#039;%@^>http://r.iirose.com/i/20/3/14/17/0704-69.png>1>'h备注>>1594276430>847fc1
    public function isPacket($message, $firstChar, $count, $explode)
    {
        if ($count == 7
            && substr($message, 0, 2)=='@*'
            && substr($explode[3], 0, 2)=='\'h') {
            return true;
        }
    }

    public function pharse($message)
    {
        $a = Bot::decode(explode('>', substr($message, 2)));
        return new UnlikeEvent(
            $a[6],
            $a[0],
            $a[3],
            $a[1],
            $a[5],
            substr($a[3], 2)
        );
    }
}
