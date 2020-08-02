<?php

namespace Bot\Provider\IIROSE;

interface Resolver
{
    public function isPacket($message, $firstChar, $count, $explode);

    public function pharse($message);
}
