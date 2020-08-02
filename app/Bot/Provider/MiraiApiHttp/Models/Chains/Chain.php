<?php

namespace Bot\Provider\MiraiApiHttp\Models\Chains;

use Bot\Provider\MiraiApiHttp\Pharse;
use JsonSerializable;

abstract class Chain implements JsonSerializable
{
    use Pharse;
    public $type;
    public function jsonSerialize()
    {
        $result=(array)$this;
        $result['type']=class_basename($this);
        return $result;
    }
}