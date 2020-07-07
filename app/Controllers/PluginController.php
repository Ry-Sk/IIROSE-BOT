<?php
namespace Controllers;
use Bot\Models\Plugin;
use Exceptions\NoAccessException;
use Exceptions\ParmsCheckException;
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
        $plugin=Plugin::find($botPlugin->slug);
        return new JsonResponse([
            'page'=>$plugin->getConfigurePage($botPlugin->configure)
        ]);
    }
    public function update(Request $request){
        $bot=Bot::auth($request->getOrFail('token'));
        $configureSource=$request->getOrFail('configure');
        $botPlugin=BotPlugin::findOrFail($request->getOrFail('id'));
        if($botPlugin->bot_id!=$bot->id){
            throw new NoAccessException();
        }
        try {
            $configure = json_decode($configureSource);
        }catch (\Exception $exception){
            throw new ParmsCheckException();
        }
        $botPlugin->configure=$configureSource;
        $botPlugin->save();
        return new JsonResponse();
    }
}