<?php


namespace Bot\Provider\MiraiApiHttp\Api;


use Bot\Provider\MiraiApiHttp\MiraiApiHttpProvider;

abstract class GetSessionApi extends GetApi
{
    public $sessionKey;
    public function __construct()
    {
        $this->sessionKey = MiraiApiHttpProvider::$instance->session;
    }
}