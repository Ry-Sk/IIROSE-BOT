<?php
namespace Controllers;
use Bot\Models\Plugin;
use Exceptions\NoAccessException;
use Exceptions\ParmsCheckException;
use Exceptions\PermissionDenyException;
use Exceptions\PluginExistException;
use Exceptions\PluginNonExistException;
use Http\Request;
use Http\Responses\JsonResponse;
use Models\Bot;
use Models\BotPlugin;

class PluginController extends \Controller\Controllers\Controller
{
    public function list(Request $request){
        $bot=Bot::authOrFail($request->getOrFail('token'));
        $botPlugins=BotPlugin::findByBot($bot);
        return new JsonResponse([
            'data'=>$botPlugins
        ]);
    }
    public function getDetail(Request $request){
        $bot=Bot::authOrFail($request->getOrFail('token'));
        $botPlugin=BotPlugin::findOrFail($request->getOrFail('id'));
        if($botPlugin->bot_id!=$bot->id){
            throw new PermissionDenyException();
        }
        $plugin=Plugin::find($botPlugin->slug);
        return new JsonResponse([
            'page'=>$plugin->getConfigurePage(json_decode($botPlugin->configure,true))
        ]);
    }
    public function update(Request $request){
        $bot=Bot::authOrFail($request->getOrFail('token'));
        $configureSource=$request->getOrFail('configure');
        $botPlugin=BotPlugin::findOrFail($request->getOrFail('id'));
        $plugin=Plugin::find($botPlugin->slug);
        if($botPlugin->bot_id!=$bot->id){
            throw new PermissionDenyException("不是你的bot");
        }
        try {
            $configure = json_decode($configureSource);
        }catch (\Exception $exception){
            throw new ParmsCheckException();
        }
        $botPlugin->configure=$plugin->verifyConfigure(json_decode($configureSource,true));
        $botPlugin->saveOrFail();
        return new JsonResponse();
    }
    public function addPlugin(Request $request){
        $bot=Bot::authOrFail($request->getOrFail('token'));
        $plugin=Plugin::find($request->getOrFail("slug"));
        $botPlugin=BotPlugin
            ::where("bot_id","=",$bot->id)
            ->where("slug","=",$request->getOrFail("slug"))
            ->first();
        if($botPlugin){
            throw new PluginExistException();
        }
        $botPlugin=new BotPlugin();
        $botPlugin->bot_id=$bot->id;
        $botPlugin->slug=$request->get("slug");
        $botPlugin->configure=json_encode($plugin->getDefaultConfig());
        $botPlugin->saveOrFail();
        return new JsonResponse();
    }
    public function removePlugin(Request $request){
        $bot=Bot::authOrFail($request->getOrFail('token'));
        $botPlugin=BotPlugin::findOrFail($request->getOrFail("id"));
        if(!$botPlugin){
            throw new PluginNonExistException();
        }
        if($botPlugin->bot_id!=$bot->id){
            throw new PermissionDenyException("不是你的bot");
        }
        $botPlugin->delete();
        return new JsonResponse();
    }
}