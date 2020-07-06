<?php
namespace Bot;

interface Handler
{
    public function isPacket($message,$firstChar,$count,$explode);

    public function pharse($message);
}