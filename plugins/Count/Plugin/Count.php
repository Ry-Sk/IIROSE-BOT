<?php


namespace Plugin\Count;

use Bot\Event\ChatEvent;
use Bot\Event\CommandEvent;
use Bot\Packets\ChatPacket;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Console\ErrorFormat;
use DB\DataBase;
use GuzzleHttp\Client;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Plugin\Count\Models\PluginCount;

class Count extends PhpPlugin
{
    public function __construct($bot, $config, $pluginLoader)
    {
        parent::__construct($bot, $config, $pluginLoader);
        if(!DataBase::schema()->hasTable('plugin_counts')){
            DataBase::schema()->create('plugin_counts', function (Blueprint $table) {
                $table->unsignedInteger('id',true);
                $table->unsignedInteger('bot_id');
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('count');
                $table->date('last');
            });
        }
    }

    public function onChat(ChatEvent $event)
    {
        /** @var PluginCount $pluginCount */
        $pluginCount=PluginCount
            ::where('bot_id','=',$this->bot->id)
            ->where('user_id','=',$event->user_id)
            ->first();
        if(!$pluginCount){
            $pluginCount=new PluginCount();
            $pluginCount->bot_id=$this->bot->id;
            $pluginCount->user_id=$event->user_id;
            $pluginCount->count=0;
            $pluginCount->last=Date::today();
        }
        if(!Date::today()->isSamedAY($pluginCount->last)){
            $pluginCount->count=0;
        }
        $pluginCount->count++;
        $pluginCount->saveOrFail();
    }

    private function count($user_name)
    {
        /** @var PluginCount $pluginCount */
        $pluginCount=PluginCount
            ::where('bot_id','=',$this->bot->id)
            ->where('user_id','=',$this->bot->getUserId($user_name))
            ->first();
        if(!Date::today()->isSamedAY($pluginCount->last)){
            $pluginCount->count=0;
        }
        return '=========发言统计=========
用户： [*'.$user_name.'*] 
机器人： [*'.$this->bot->username.'*] 
时间：'.date('Y-m-d').'
发言数：'.$pluginCount->count;
    }
    private function room()
    {
        $chatCount=PluginCount
            ::where('bot_id','=',$this->bot->id)
            ->where('last','=',Date::today())
            ->sum('count');
        $userCount=PluginCount
            ::where('bot_id','=',$this->bot->id)
            ->where('last','=',Date::today())
            ->count();
        return '=========机器人统计=========
机器人： [*'.$this->bot->username.'*] 
时间：'.date('Y-m-d').'
发言数：'.$chatCount.'
发言人数：'.$userCount;
    }
    public function onCommand(CommandEvent $event)
    {
        if($event->sign=='count:bot'){
            $event->output->write($this->room());
        }elseif ($event->sign=='count:user'){
            $arg=$event->input->getArgument('who');
            if($arg){
                $event->output->write($this->count(substr($arg,2,strlen($arg)-4)));
            }else{
                $event->output->write($this->count($event->sender->getUsername()));
            }
        }
    }
}
