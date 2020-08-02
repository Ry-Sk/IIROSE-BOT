<?php


namespace Bot\Provider\MiraiApiHttp;


trait Pharse
{
    public function pharse($message){
        foreach ($message as $k=>$v){
            $this->$k=$v;
        }
        return $this;
    }
}