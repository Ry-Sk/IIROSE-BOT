<?php
namespace Bot\PluginLoader\JsPlugin;

class JsPhpProvider
{
    public function getStatic($name)
    {
        return new JsPhpClass($name);
    }
    public function createInstance($name,...$prams)
    {
        return (new JsPhpClass($name))->newInstance($prams);
    }
}
