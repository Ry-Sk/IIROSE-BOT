<?php


namespace Bot\PluginLoader\JsPlugin;

class JsPackets
{
    public function __get($name)
    {
        return function ($args = null, $_ = null) use ($name) {
            $class=new \ReflectionClass('Bot\\Packets\\'.$name);
            return $class->newInstanceArgs(func_get_args());
        };
    }
}
