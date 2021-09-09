<?php
namespace Bot\Models;

use Bot\Exception\BotException;
use Bot\PluginLoader;
use Exceptions\PluginNonExistException;
use Exceptions\ValidationException;
use File\File;
use File\Path;
use Illuminate\Support\Str;
use Model\Models\Model;
use Models\Bot;
use Parsedowns\ConfigureParse;
use Vaildator\Vaildator;

class Plugin
{
    /** @var PluginLoader */
    protected $pluginLoader;
    protected $pluginLoaderClass;
    protected $info;
    protected $slug;
    protected $basePath;
    /** @var Bot $bot */
    protected $bot;
    public static function find($slug)
    {
        if (!$slug) {
            throw new PluginNonExistException();
        }
        $plugin=new self();
        $plugin->slug=$slug;
        $plugin->basePath=Path::get_absolute_path(ROOT.'/plugins/'.$plugin->slug);
        if (!is_dir($plugin->basePath)) {
            throw new PluginNonExistException();
        }
        $plugin->info=json_decode(file_get_contents($plugin->basePath.'/plugin.json'));
        $classSlug=strtoupper(substr($plugin->info->loader, 0, 1)).substr($plugin->info->loader, 1);
        $plugin->pluginLoaderClass='\\Bot\\PluginLoader\\'.$classSlug.'PluginLoader';
        return $plugin;
    }

    public function getConfig()
    {
        $doms=json_decode(file_get_contents($this->basePath.'/config.json'), true);
        $result=[];
        foreach ($doms as $dom) {
            if (isset($dom['_name'])) {
                $answer=[];
                $answer['_name']=$dom['_name'];
                if (isset($dom['_default'])) {
                    $answer['_default']=$dom['_default'];
                }
                if (isset($dom['_validate'])) {
                    $answer['_validate']=$dom['_validate'];
                }
                $result[]=$answer;
            }
        }
        return $result;
    }
    public function getDefaultConfig()
    {
        $result=[];
        foreach ($this->getConfig() as $dom) {
            $result[$dom['_name']]=isset($dom['_default'])?$dom['_default']:null;
        }
        return $result;
    }
    public function getConfigurePage($configure)
    {
        var_dump($configure);
        $doms=json_decode(file_get_contents($this->basePath.'/config.json'), true);
        $result=[];
        foreach ($doms as $dom) {
            if (isset($dom['_name'])) {
                $dom['_value']=$configure[$dom['_name']];
                $result[]=$dom;
            }
        }
        return $result;
        /**$parser=new ConfigureParse(
            $this->getConfig(),
            $configure
        );
        return $parser->parse(file_get_contents($this->basePath.'/config.md'));**/
    }
    public function verifyConfigure($configure)
    {
        $result=[];
        $verify=[];
        foreach ($this->getConfig() as $dom) {
            $result[$dom['_name']]=@$configure[$dom['_name']];
            $verify[$dom['_name']]=@$dom['_validate']?:'required';
        }
        $validator= Vaildator::getInstance()->make($result, $verify)->errors()->all();
        if ($validator) {
            throw new ValidationException(json_encode($validator));
        }
        return $result;
    }



    /**
     * @param Bot $bot
     * @param $configure
     */
    public function load($bot, $configure)
    {
        $this->bot=$bot;
        $this->pluginLoader = new $this->pluginLoaderClass($this->bot, $configure, $this->slug);
        $this->pluginLoader->load();
        $commands=json_decode(file_get_contents($this->basePath.'/commands.json'));
        foreach ($commands as $command) {
            $this->bot->addCommand($command);
        }
    }

    public function reload($configure)
    {
        $this->pluginLoader->reload($configure);
    }

    public function unload()
    {
        $commands=json_decode(file_get_contents($this->basePath.'/commands.json'));
        foreach ($commands as $command) {
            $this->bot->removeCommand($command);
        }
        $this->pluginLoader->unload();
    }

    public function tick()
    {
        $this->pluginLoader->tick();
    }
}
