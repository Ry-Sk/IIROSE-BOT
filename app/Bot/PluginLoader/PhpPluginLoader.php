<?php
namespace Bot\PluginLoader;

use Bot\AutoListener;
use Bot\Listener;
use Bot\Listenerable;
use Bot\PluginLoader;

class PhpPluginLoader extends PluginLoader implements Listenerable
{
    use AutoListener;
    public $plugin;
    public function load()
    {
        $this->load=true;
        $this->bot->getAutoLoader()->add('Plugin\\' . $this->slug, ROOT . '/plugins/PHP/' . $this->slug . '/Plugin');
        $plugin_class = '\\Plugin\\' . $this->slug . '\\' . $this->slug;
        $this->plugin = new $plugin_class($this->bot, $this->configure, $this);
        $this->registerListeners($this->plugin);
    }
}