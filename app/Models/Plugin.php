<?php
namespace Models;

use Bot\PluginLoader;
use Bot\PluginLoaders;
use Model\Models\Model;

/**
 * Class Plugin
 * @package Models
 * @adminphp start
 * @property $id
 * @property $loader
 * @property $slug
 * @method static Plugin find(int $id)
 * @method static Plugin findOrFail(int $id)
 * @method static \Illuminate\Database\Query\Builder where(\Closure|string|array $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @adminphp end
 */
class Plugin extends Model
{
    /** @var PluginLoader */
    protected $pluginLoader;
    public function loading($bot, $configure)
    {
        $loader_class=PluginLoaders::getLoader($this->loader);
        $this->pluginLoader=new $loader_class($bot, $configure,$this->slug);
        $this->pluginLoader->load();
    }
    public function reload($configure){
        $this->pluginLoader->reload($configure);
    }
}