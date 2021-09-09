<?php


namespace Bot\Provider\MiraiApiHttp\Models;


use Bot\Provider\MiraiApiHttp\Api\SendGroupMessageApi;
use Bot\Provider\MiraiApiHttp\Pharse;

class Group
{
    use Pharse;
    public $id;
    public $name;
    public $permission;
    public function sendChat($messageChain){
        (new SendGroupMessageApi())->setMessageChain($messageChain)->setTarget($this->id)->push();
    }
}