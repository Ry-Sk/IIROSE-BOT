<?php
namespace Plugin\RSS\Models;

use Model\Models\Model;

/**
 * Class PluginRssPushes
 * @package Models
 * @adminphp start
 * @property $id
 * @property $bot_id
 * @property $guid
 * @method static PluginRssPushes find(int $id)
 * @method static PluginRssPushes findOrFail(int $id)
 * @method static \Illuminate\Database\Query\Builder where(\Closure|string|array $column, mixed $operator = null, mixed $value = null, string $boolean = 'and')
 * @adminphp end
 */
class PluginRssPushes extends Model
{
    public $timestamps = false;
}