<?php

namespace Bot\Provider\MiraiApiHttp\Models\Chains;

class Plain extends Chain
{
    public $text='';

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }
}