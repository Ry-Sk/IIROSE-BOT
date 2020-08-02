<?php


namespace Bot\Provider\MiraiApiHttp\Api;


class ReleaseApi extends PostApi
{
    public $session;
    public $qq;

    public function setSession($session)
    {
        $this->session = $session;
        return $this;
    }

    public function setQq($qq)
    {
        $this->qq = $qq;
        return $this;
    }
}