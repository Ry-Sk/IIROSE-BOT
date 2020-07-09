<?php
namespace Controllers;
use Bot\Models\Plugin;
use Controller\Controllers\Controller;
use Exceptions\NoAccessException;
use Exceptions\ParmsCheckException;
use File\File;
use File\Path;
use Http\Request;
use Http\Responses\JsonResponse;
use Models\Bot;
use Models\BotPlugin;

class PluginMarketController extends Controller
{
    public function list(Request $request){
        $bot=Bot::authOrFail($request->getOrFail('token'));
        $plugins=File::scan_dir_dirs(ROOT.'/plugins',false);
        $o=[];
        foreach ($plugins as $plugin){
            $info=json_decode(file_get_contents(Path::formt_file(ROOT.'/plugins/'.$plugin.'/plugin.json')),true);
            $info['slug']=substr($plugin,0,strlen($plugin)-1);
            $o[]=$info;
        }
        return new JsonResponse([
            'data'=>$o
        ]);
    }
}