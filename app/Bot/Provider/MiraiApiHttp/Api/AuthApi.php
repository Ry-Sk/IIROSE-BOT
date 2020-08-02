<?php


namespace Bot\Provider\MiraiApiHttp\Api;

class AuthApi extends PostApi
{
    public $authKey;

    public function setAuthKey($authKey)
    {
        $this->authKey = $authKey;
        return $this;
    }
}