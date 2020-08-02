<?php


namespace Bot\Provider\MiraiApiHttp\Models;


use Bot\Models\Sender;
use Bot\Provider\MiraiApiHttp\Api\SendGroupMessageApi;
use Bot\Provider\MiraiApiHttp\Pharse;
use Models\Bot;

class GroupSender extends Sender
{
    use Pharse;
    private $username;
    private $user_id;
    /** @var Group $group */
    private $group;

    public function getUsername()
    {
        return $this->username;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function sendMessage($message)
    {
        (new SendGroupMessageApi())->setTarget($this->getGroup()->id)->messageSetPlain($message)->push();
    }

    public function pharse($message)
    {
        $this->username=$message['memberName'];
        $this->user_id=$message['id'];
        $this->group=(new Group())->pharse($message['group']);
        return $this;
    }
}