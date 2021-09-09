<?php


use Helper\Config;
use Logger\Logger;

class Keneral
{
    public function __construct()
    {
        new Config();
        new Logger();
    }
}
