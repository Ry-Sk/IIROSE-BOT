<?php
namespace Plugin\Count\Models;

use Model\Models\Model;

/**
 * Class PluginCount
 * @package Models
 * @adminphp start
 * @property $id
 * @property $bot_id
 * @property $user_id
 * @property $count
 * @property $last
 * @method static PluginCount find(int $id)
 * @method static PluginCount findOrFail(int $id)
 * @method static \Illuminate\Database\Query\Builder where(\Closure|string|array $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @adminphp end
 */
class PluginCount extends Model
{
    public $timestamps = false;
}