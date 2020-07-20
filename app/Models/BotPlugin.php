<?php
namespace Models;

use Bot\Models\Plugin;
use Bot\PluginLoader;
use Console\ErrorFormat;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Logger\Logger;
use Model\Models\Model;

/**
 * Class Bot_plugin
 * @package Models
 * @adminphp start
 * @property $id
 * @property $bot_id
 * @property $slug
 * @property $configure
 * @method static BotPlugin find(int $id)
 * @method static BotPlugin findOrFail(int $id)
 * @method static \Illuminate\Database\Query\Builder where(\Closure|string|array $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @adminphp end
 */
class BotPlugin extends Model
{
    public $timestamps = false;

    /**
     * @param $bot
     * @return BotPlugin[]
     */
    public static function findByBot($bot)
    {
        if ($bot instanceof Bot) {
            $botPlugins = BotPlugin::where('bot_id', '=', $bot->id)->get();
        } else {
            $botPlugins =  BotPlugin::where('bot_id', '=', $bot)->get();
        }
        /** @var BotPlugin[] $botPlugins */
        return $botPlugins;
    }

    /** @var Bot $bot */
    protected $bot;
    /** @var Plugin $plugin */
    protected $plugin;
    protected $isload;
    public function loading($bot)
    {
        if ($this->isload) {
            return;
        }
        Logger::info('加载插件'.$this->id.':'.$this->slug);
        $this->isload=true;
        $this->bot=$bot;
        $this->plugin=Plugin::find($this->slug);
        $this->plugin->load($bot, $this->configure);
    }
    public function check()
    {
        try {
            $config=$this->configure;
            $this->refresh();
            if ($config!=$this->configure) {
                Logger::info('重载插件'.$this->id.':'.$this->slug);
                $this->plugin->reload($this->configure);
            }
        } catch (ModelNotFoundException $e) {
            Logger::info('卸载插件'.$this->id.':'.$this->slug);
            $this->plugin->unload();
            $this->isload=false;
            return;
        } catch (\Throwable $e) {
            ErrorFormat::dump($e);
        }
    }
    public function tick()
    {
        if ($this->plugin) {
            $this->plugin->tick();
        }
    }
}
