<?php

namespace Bot\Event;


use Bot\Models\Sender;

abstract class PersonChatEvent extends Event
{
    public abstract function getMessage();
    public abstract function getUserId();
    public abstract function getUsername();
    /**
     * @return Sender
     */
    public abstract function getSender();
}