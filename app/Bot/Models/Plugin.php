<?php
namespace Bot\Models;

use Bot\Exception\BotException;
use Bot\PluginLoader;
use File\File;
use File\Path;
use Illuminate\Support\Str;
use Model\Models\Model;
use Models\Bot;
use Parsedowns\ConfigureParse;

class Plugin
{
    /** @var PluginLoader */
    protected $pluginLoader;
    protected $pluginLoaderClass;
    protected $info;
    protected $slug;
    protected $basePath;
    public static function find($slug){
        $plugin=new self();
        $plugin->slug=$slug;
        $plugin->basePath=Path::get_absolute_path(ROOT.'/plugins/'.$plugin->slug);
        if(!is_dir($plugin->basePath)){
            throw new BotException();
        }
        $plugin->info=json_decode(file_get_contents($plugin->basePath.'/plugin.json'));
        $classSlug=strtoupper(substr($plugin->info->loader,0,1)).substr($plugin->info->loader,1);
        $plugin->pluginLoaderClass='\\Bot\\PluginLoader\\'.$classSlug.'PluginLoader';
        return $plugin;
    }

    public function getConfig(){
        return json_decode(file_get_contents($this->basePath.'/config.json'),true);
    }
    public function getConfigurePage($configure){
        $parser=new ConfigureParse(
            $this->getConfig(),
            $configure
        );
        return $parser->parse(file_get_contents($this->basePath.'/config.md'));
    }

    /**
     * @param Bot $bot
     * @param $configure
     */
    public function load($bot, $configure)
    {
        $this->pluginLoader = new $this->pluginLoaderClass($bot, $configure,$this->slug);
        $this->pluginLoader->load();
        $commands=json_decode(file_get_contents($this->basePath.'/commands.json'));
        foreach ($commands as $command){
            $bot->addCommand($command);
        }
    }

    public function reload($configure){
        $this->pluginLoader->reload($configure);
    }
}