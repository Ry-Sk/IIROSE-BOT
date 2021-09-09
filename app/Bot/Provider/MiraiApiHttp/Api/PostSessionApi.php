<?php


namespace Bot\Provider\MiraiApiHttp\Api;


use Bot\Provider\MiraiApiHttp\MiraiApiHttpProvider;
use Models\Bot;

abstract class PostSessionApi extends PostApi
{
    public $sessionKey;
    public function __construct()
    {
        $this->sessionKey = MiraiApiHttpProvider::$instance->session;
    }
}