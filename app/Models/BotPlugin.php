<?php
namespace Models;

use Model\Models\Model;

/**
 * Class Bot_plugin
 * @package Models
 * @adminphp start
 * @property $id
 * @property $bot_id
 * @property $plugin_id
 * @property $configure
 * @method static BotPlugin find(int $id)
 * @method static BotPlugin findOrFail(int $id)
 * @method static \Illuminate\Database\Query\Builder where(\Closure|string|array $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @adminphp end
 */
class BotPlugin extends Model
{
    /** @var Bot $bot */
    protected $bot;
    protected $plugin;
    public function loading($bot){
        $this->bot=$bot;
        $this->plugin=Plugin::findOrFail($this->plugin_id);
        $this->plugin->loading($bot,$this->configure);
    }
}