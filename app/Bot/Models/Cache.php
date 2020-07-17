<?php


namespace Bot\Models;


class Cache
{
    public $time;
    public $data;
    public function __construct($data)
    {
        $this->data=$data;
        $this->time=time();
    }

    public function isExpire($timeout=120){
        return $this->time < time()-$timeout;
    }
}