<?php
namespace Bot\Extensions;

use Bot\Event\InfoEvent;
use Bot\Event\NoUserEvent;
use Bot\Event\UserInfoEvent;
use Bot\Packets\InfoPacket;
use Logger\Logger;
use Models\Bot;

trait AsyncInfoExtension
{
    private $asyncInfoExtensionLock;
    private $asyncInfoExtensionAnswer;
    private $asyncInfoExtensionCache=[];

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
                return $this->asyncInfoExtensionAnswer;
            }
            \Co::sleep(0.1);
        }
        $this->asyncInfoExtensionLock = false;
        return null;
    }
    public function getUserId($user_name)
    {
        if(@$this->asyncInfoExtensionCache[$user_name]
            && $this->asyncInfoExtensionCache[$user_name]>time()-120){
            return $this->asyncInfoExtensionCache[$user_name]->user_id;
        }
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
                return $this->asyncInfoExtensionAnswer->user_id;
            }
            \Co::sleep(0.1);
        }
        $this->asyncInfoExtensionLock = false;
        return null;
    }

    public function asyncInfoExtensionOnInfoEvent(InfoEvent $event){
        $this->asyncInfoExtensionAnswer = $event;
    }
    public function asyncInfoExtensionOnNoUserEvent(NoUserEvent $event){
        $this->asyncInfoExtensionAnswer = false;
    }
    public function asyncInfoExtensionOnUserInfoEvent(UserInfoEvent $event){
        $this->asyncInfoExtensionCache[$event->username]=$event;
    }
}