<?php


namespace Bot\Packets;

use Models\Bot;

class AdminForbiddenGet implements \Bot\Packet
{
    public function compile()
    {
        return '!h3'.json_encode(['3']);
    }
}
