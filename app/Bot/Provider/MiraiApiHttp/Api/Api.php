<?php


namespace Bot\Provider\MiraiApiHttp\Api;

use GuzzleHttp\Exception\GuzzleException;
use stdClass;

abstract class Api
{
    /**
     * @return stdClass|array
     * @throws GuzzleException
     */
    public abstract function push();
}