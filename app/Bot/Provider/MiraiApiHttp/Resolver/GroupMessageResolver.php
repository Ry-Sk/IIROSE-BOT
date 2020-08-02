<?php
namespace Bot\Provider\MiraiApiHttp\Resolver;


use Bot\Provider\MiraiApiHttp\Event\ChatEvent;
use Bot\Provider\MiraiApiHttp\Models\GroupSender;
use Bot\Provider\MiraiApiHttp\Resolver;
use Models\Bot;

class GroupMessageResolver implements Resolver
{
    public static function pharse($message)
    {
        return (new ChatEvent())
            ->messagePharse($message['messageChain'])
            ->setSender((new GroupSender())->pharse($message['sender']));
    }
}
