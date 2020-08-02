<?php

namespace Bot\Provider\MiraiApiHttp\Models;

use Bot\Provider\MiraiApiHttp\Models\Chains\Chain;
use Bot\Provider\MiraiApiHttp\Models\Chains\Plain;
use Logger\Logger;

trait MessageChain
{
    /** @var Chain[] $messageChain */
    public $messageChain=[];
    public function messageSetPlain($message){
        $this->messageChain=[
            (new Plain())->setText($message),
        ];
        return $this;
    }
    public function messageGetPlain(){
        $result='';
        foreach ($this->messageChain as $p){
            if($p instanceof Plain){
                $result.=$p->text;
            }
        }
        return $result;
    }
    public function messagePharse($message){
        //var_dump($message);
        $this->messageChain=[];
        foreach ($message as $p){
            $chainClass='Bot\\Provider\\MiraiApiHttp\\Models\\Chains\\'.$p['type'];
            if(class_exists($chainClass)){
                $this->messageChain[] = (new $chainClass())->pharse($p);
            }else{
                Logger::info('未知的消息节点：'.$p['type']);
            }
        }
        return $this;
    }
    public function setMessageChain($messageChain)
    {
        $this->messageChain = $messageChain;
        return $this;
    }
}