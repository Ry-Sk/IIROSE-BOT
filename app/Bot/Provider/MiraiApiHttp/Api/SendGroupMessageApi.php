<?php


namespace Bot\Provider\MiraiApiHttp\Api;


use Bot\Provider\MiraiApiHttp\Models\MessageChain;

class SendGroupMessageApi extends PostSessionApi
{
    use MessageChain;
    public $target;
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }

}