<?php


namespace Bot\Provider\MiraiApiHttp\Api;


class VerifyApi extends PostSessionApi
{
    public $qq;

    public function setQq($qq)
    {
        $this->qq = $qq;
        return $this;
    }
}