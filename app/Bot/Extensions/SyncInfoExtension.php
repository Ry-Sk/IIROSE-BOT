<?php
namespace Bot\Extensions;

use Bot\Models\Cache;
use Bot\Provider\IIROSE\Event\ChatEvent;
use Bot\Provider\IIROSE\Event\GotoEvent;
use Bot\Provider\IIROSE\Event\InfoEvent;
use Bot\Provider\IIROSE\Event\JoinEvent;
use Bot\Provider\IIROSE\Event\LeaveEvent;
use Bot\Provider\IIROSE\Event\NoUserEvent;
use Bot\Provider\IIROSE\Event\PersonChatEvent;
use Bot\Provider\IIROSE\Event\UserInfoEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Packets\InfoPacket;

trait SyncInfoExtension
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
        IIROSEProvider::$instance->packet(new InfoPacket($user_name));
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
        if (@$this->userId[$user_name]
            && $this->userId[$user_name]->isExpire()) {
            return $this->userId[$user_name]->user_id;
        } else {
            $info=$this->getInfo($user_name);
            if (is_null($info)) {
                return null;
            }
            return $info->user_id;
        }
    }

    public function syncInfoExtensionOnInfoEvent(InfoEvent $event)
    {
        $this->answer = $event;
    }
    public function syncInfoExtensionOnNoUserEvent(NoUserEvent $event)
    {
        $this->aanswer = false;
    }
    public function syncInfoExtensionOnUserInfoEvent(UserInfoEvent $event)
    {
        $this->userId[$event->username]=new Cache($event->user_id);
        $this->cache[$event->username]=$event;
    }
    public function syncInfoExtensionOnChatEvent(ChatEvent $event)
    {
        $this->userId[$event->user_name]=new Cache($event->user_id);
    }
    public function syncInfoExtensionOnPersonChatEvent(PersonChatEvent $event)
    {
        $this->userId[$event->user_name]=new Cache($event->user_id);
    }
    public function syncInfoExtensionOnJoinEvent(JoinEvent $event)
    {
        $this->userId[$event->user_name]=new Cache($event->user_id);
    }
    public function syncInfoExtensionOnGotoEvent(GotoEvent $event)
    {
        $this->userId[$event->user_name]=new Cache($event->user_id);
    }
    public function syncInfoExtensionOnLeaveEvent(LeaveEvent $event)
    {
        $this->userId[$event->user_name]=new Cache($event->user_id);
    }
}
