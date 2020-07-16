<?php
namespace Bot\PluginLoader\JsPlugin;

class JsEvents
{
    public function __get($name)
    {
        return 'Bot\\Event\\'.$name;
    }
}