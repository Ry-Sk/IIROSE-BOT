<?php
namespace Bot\PluginLoader;

use Bot\Extensions\AutoListener;
use Bot\Listener;
use Bot\PluginLoader;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;

class PhpPluginLoader extends PluginLoader
{
    use AutoListener;
    /** @var PhpPlugin $plugin */
    public $plugin;
    public function load()
    {
        $this->load=true;
        $this->bot->getAutoLoader()->add('Plugin\\' . $this->slug, ROOT . '/plugins/' . $this->slug . '/Plugin');
        $plugin_class = '\\Plugin\\' . $this->slug . '\\' . $this->slug;
        $this->plugin = new $plugin_class($this->bot, $this->configure, $this);
        $this->registerListener($this->plugin);
    }
    public function unload()
    {
        unset($this->plugin);
        parent::unload();
    }
    public function tick()
    {
        parent::tick();
        $this->plugin->tick();
    }

    function loaded()
    {
        return $this->load;
    }
}
