<?php
namespace Bot\Extensions;

use Bot\Event\ChatEvent;
use Bot\Event\GotoEvent;
use Bot\Event\InfoEvent;
use Bot\Event\JoinEvent;
use Bot\Event\LeaveEvent;
use Bot\Event\NoUserEvent;
use Bot\Event\PersonChatEvent;
use Bot\Event\UserInfoEvent;
use Bot\Models\Cache;
use Bot\Packets\InfoPacket;
use Logger\Logger;
use Models\Bot;
//TODO: 别想用这个，EVENT Handler都没有做好。
trait SyncAdminExtension
{
    private $lock;
    /** @var InfoEvent $answer */
    private $answer;
    /** @var UserInfoEvent[] $cache */
    private $cache=[];
    /** @var Cache[] $userId */
    private $userId=[];

    public function getInfo($user_name)
    {
        while (true) {
            if (!$this->lock) {
                $this->lock = true;
                break;
            } else {
                \Co::sleep(0.1);
            }
        }
        $this->answer = null;
        Bot::$instance->packet(new InfoPacket($user_name));
        for ($i = 0; $i < 50; $i++) {
            if ($this->answer !== null) {
                $this->lock = false;
                $this->userId[$user_name]=new Cache($this->answer->user_id);
                return $this->answer;
            }
            \Co::sleep(0.1);
        }
        $this->lock = false;
        return null;
    }
    public function getUserId($user_name)
    {
        if(@$this->userId[$user_name]
            && $this->userId[$user_name]->isExpire()){
            return $this->userId[$user_name]->user_id;
        }else{
            $info=$this->getInfo($user_name);
            if(is_null($info)){
                return null;
            }
            return $info->user_id;
        }
    }

    public function syncInfoExtensionOnInfoEvent(InfoEvent $event){
        $this->answer = $event;
    }
    public function syncInfoExtensionOnNoUserEvent(NoUserEvent $event){
        $this->aanswer = false;
    }
    public function syncInfoExtensionOnUserInfoEvent(UserInfoEvent $event){
        $this->userId[$event->username]=new Cache($event->user_id);
        $this->cache[$event->username]=$event;
    }
    public function syncInfoExtensionOnChatEvent(ChatEvent $event){
        $this->userId[$event->user_name]=new Cache($event->user_id);
    }
    public function syncInfoExtensionOnPersonChatEvent(PersonChatEvent $event){
        $this->userId[$event->user_name]=new Cache($event->user_id);
    }
    public function syncInfoExtensionOnJoinEvent(JoinEvent $event){
        $this->userId[$event->user_name]=new Cache($event->user_id);
    }
    public function syncInfoExtensionOnGotoEvent(GotoEvent $event){
        $this->userId[$event->user_name]=new Cache($event->user_id);
    }
    public function syncInfoExtensionOnLeaveEvent(LeaveEvent $event){
        $this->userId[$event->user_name]=new Cache($event->user_id);
    }
}