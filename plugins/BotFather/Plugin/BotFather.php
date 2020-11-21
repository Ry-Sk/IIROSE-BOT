<?php


namespace Plugin\BotFather;

use Bot\Event\ChatEvent;
use Bot\Event\CommandEvent;
use Bot\Models\Plugin;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Bot\Provider\IIROSE\IIROSEProvider;
use GuzzleHttp\Client;
use Models\Bot;
use Models\BotPlugin;

class BotFather extends PhpPlugin
{
    public function onCommand(CommandEvent $event)
    {
        if($event->sign=='botFather:login'){
            $username=$event->input->getArgument('username');
            $password=$event->input->getArgument('password');
            $client = new Client();
            $response = $client->post(
                'https://a.iirose.com/lib/php/system/login_member_ajax.php',
                [
                    'form_params'=>[
                        'n'=>$username,
                        'p'=>md5($password),
                    ]
                ]
            );
            if ($response
                && $response->getBody()) {
                $content=$response->getBody()->getContents();
                if (strlen($content)==13) {
                    $uid=$content;
                    /** @var Bot $bot */
                    $bot= Bot::where('uid', '=', $uid)->first();
                    if (!$bot) {
                        $bot=new Bot();
                        $bot->uid=$uid;
                        $bot->username=$username;
                        $bot->password=$password;
                        $bot->token=uniqid('login');
                    }
                    $bot->enable=2;
                    $bot->room=IIROSEProvider::$instance->getUserInfo($event->sender->getUsername())->room_id;
                    $bot->saveOrFail();
                    $botPlugin=BotPlugin::where('bot_id',$bot->id)->where('slug','BotFather')->first();
                    if(!$botPlugin){
                        $botPlugin=new BotPlugin();
                    }
                    $botPlugin->bot_id=$bot->id;
                    $botPlugin->slug='BotFather';
                    $botPlugin->configure=json_encode(['master'=>$event->sender->getUserId()]);
                    $botPlugin->save();
                    $event->sender->sendMessage('success');
                    return;
                } else {
                    $event->sender->sendMessage("密码错误");
                    return;
                }
            }
            $event->sender->sendMessage("无法连接API");
        }
        if($event->sign=='botFather:signout'){
            $username=$event->input->getArgument('username');
            $password=$event->input->getArgument('password');
            $client = new Client();
            $response = $client->post(
                'https://a.iirose.com/lib/php/system/login_member_ajax.php',
                [
                    'form_params'=>[
                        'n'=>$username,
                        'p'=>md5($password),
                    ]
                ]
            );
            if ($response
                && $response->getBody()) {
                $content=$response->getBody()->getContents();
                if (strlen($content)==13) {
                    $uid=$content;
                    /** @var Bot $bot */
                    $bot= Bot::where('uid', '=', $uid)->first();
                    if (!$bot) {
                        $bot=new Bot();
                        $bot->uid=$uid;
                        $bot->username=$username;
                        $bot->password=$password;
                        $bot->token=uniqid('login');
                    }
                    $bot->enable=0;
                    $bot->room=IIROSEProvider::$instance->getUserInfo($event->sender->getUsername())->room_id;
                    $bot->saveOrFail();
                    $botPlugin=BotPlugin::where('bot_id',$bot->id)->where('slug','BotFather')->first();
                    if(!$botPlugin){
                        $botPlugin=new BotPlugin();
                    }
                    $botPlugin->bot_id=$bot->id;
                    $botPlugin->slug='BotFather';
                    $botPlugin->configure=json_encode(['master'=>$event->sender->getUserId()]);
                    $botPlugin->save();
                    $event->sender->sendMessage('success');
                    return;
                } else {
                    $event->sender->sendMessage("密码错误");
                    return;
                }
            }
            $event->sender->sendMessage("无法连接API");
        }
        if(substr($event->sign,0,10)=='botFather:'){
            if($event->sender->getUserId()!=$this->config['master']){
                $event->sender->sendMessage('呜呜呜，你不是我的主人');
                return;
            }
        }

        if($event->sign=='botFather:here'){
            $event->sender->sendMessage('bye');
            Bot::$instance->setRoom(IIROSEProvider::$instance->getUserInfo($event->sender->getUsername())->room_id);
            return;
        }
        if($event->sign=='botFather:enablePlugin'){
            $pluginName=$event->input->getArgument('plugin');
            $plugin=Plugin::find($pluginName);
            /** @var BotPlugin botPlugin */
            $botPlugin=BotPlugin::where('bot_id',$this->bot->id)->where('slug',$pluginName)->first();
            if($botPlugin){
                $event->sender->sendMessage('插件已经在运行了');
            }else{
                $botPlugin=new BotPlugin();
                $botPlugin->bot_id=$this->bot->id;
                $botPlugin->slug=$pluginName;
                $botPlugin->configure=json_encode($plugin->getDefaultConfig());
                $botPlugin->save();
                $event->sender->sendMessage('完工啦');
            }
        }
        if($event->sign=='botFather:disablePlugin'){
            $pluginName=$event->input->getArgument('plugin');
            $plugin=Plugin::find($pluginName);
            /** @var BotPlugin botPlugin */
            $botPlugin=BotPlugin::where('bot_id',$this->bot->id)->where('slug',$pluginName)->first();
            if($botPlugin){
                $botPlugin->delete();
                $event->sender->sendMessage('插件已禁用');
            }else{
                $event->sender->sendMessage('未发现启用的插件');
            }
        }
        if($event->sign=='botFather:listPlugin'){
            /** @var BotPlugin[] botPlugin */
            $botPlugins=BotPlugin::where('bot_id',$this->bot->id)->get();
            $op='';
            foreach($botPlugins as $botPlugin){
                $op.=$botPlugin->slug."\n";
            }
            $event->sender->sendMessage($op);
        }
    }
}
