<?php


namespace Bot\Provider\IIROSE\Packets;

use Bot\Provider\IIROSE\Packet;

class AdminForbiddenGet implements Packet
{
    public function compile()
    {
        return '!h3'.json_encode(['3']);
    }
}
