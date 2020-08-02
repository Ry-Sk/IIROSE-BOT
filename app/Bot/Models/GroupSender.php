<?php


namespace Bot\Models;

abstract class GroupSender extends Sender
{
    public abstract function getGroupId();
}
