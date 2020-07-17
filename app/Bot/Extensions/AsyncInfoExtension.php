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

trait AsyncInfoExtension
{
    private $asyncInfoExtensionLock;
    /** @var InfoEvent $asyncInfoExtensionAnswer */
    private $asyncInfoExtensionAnswer;
    /** @var UserInfoEvent[] $asyncInfoExtensionCache */
    private $asyncInfoExtensionCache=[];
    /** @var Cache[] $asyncInfoExtensionCache */
    private $asyncInfoExtensionUserId=[];

    public function getInfo($user_name)
    {
        while (true) {
            if (!$this->asyncInfoExtensionLock) {
                $this->asyncInfoExtensionLock = true;
                break;
            } else {
                \Co::sleep(0.1);
            }
        }
        $this->asyncInfoExtensionAnswer = null;
        Bot::$instance->packet(new InfoPacket($user_name));
        for ($i = 0; $i < 50; $i++) {
            if ($this->asyncInfoExtensionAnswer !== null) {
                $this->asyncInfoExtensionLock = false;
                $this->asyncInfoExtensionUserId[$user_name]=new Cache($this->asyncInfoExtensionAnswer->user_id);
                return $this->asyncInfoExtensionAnswer;
            }
            \Co::sleep(0.1);
        }
        $this->asyncInfoExtensionLock = false;
        return null;
    }
    public function getUserId($user_name)
    {
        if(@$this->asyncInfoExtensionUserId[$user_name]
            && $this->asyncInfoExtensionUserId[$user_name]->isExpire()){
            return $this->asyncInfoExtensionUserId[$user_name]->user_id;
        }else{
            $info=$this->getInfo($user_name);
            if(is_null($info)){
                return null;
            }
            return $info->user_id;
        }
    }

    public function asyncInfoExtensionOnInfoEvent(InfoEvent $event){
        $this->asyncInfoExtensionAnswer = $event;
    }
    public function asyncInfoExtensionOnNoUserEvent(NoUserEvent $event){
        $this->asyncInfoExtensionAnswer = false;
    }
    public function asyncInfoExtensionOnUserInfoEvent(UserInfoEvent $event){
        $this->asyncInfoExtensionUserId[$event->username]=new Cache($event->user_id);
        $this->asyncInfoExtensionCache[$event->username]=$event;
    }
    public function asyncInfoExtensionOnChatEvent(ChatEvent $event){
        $this->asyncInfoExtensionUserId[$event->user_name]=new Cache($event->user_id);
    }
    public function asyncInfoExtensionOnPersonChatEvent(PersonChatEvent $event){
        $this->asyncInfoExtensionUserId[$event->user_name]=new Cache($event->user_id);
    }
    public function asyncInfoExtensionOnJoinEvent(JoinEvent $event){
        $this->asyncInfoExtensionUserId[$event->user_name]=new Cache($event->user_id);
    }
    public function asyncInfoExtensionOnGotoEvent(GotoEvent $event){
        $this->asyncInfoExtensionUserId[$event->user_name]=new Cache($event->user_id);
    }
    public function asyncInfoExtensionOnLeaveEvent(LeaveEvent $event){
        $this->asyncInfoExtensionUserId[$event->user_name]=new Cache($event->user_id);
    }
}