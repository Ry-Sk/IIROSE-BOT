<?php


namespace Bot\Models;

abstract class Sender
{
    public abstract function getUsername();
    public abstract function getUserId();
    public abstract function sendMessage($message);
}
