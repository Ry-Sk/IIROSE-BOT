<?php


namespace Bot;


class PluginLoaders
{
    const map=[
        1=>\Bot\PluginLoader\PhpPluginLoader::class
    ];
    public static function getLoader($id){
        return self::map[$id];
    }
}