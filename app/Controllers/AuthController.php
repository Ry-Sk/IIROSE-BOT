<?php
namespace Controllers;
use Exceptions\AuthException;
use GuzzleHttp\Client;
use Http\Request;
use Models\Bot;

class AuthController extends \Controller\Controllers\Controller
{
    public function login(Request $request){
        $client = new Client();
        $response = $client->post(
            'https://a.iirose.com/lib/php/system/login_member_ajax.php',
            [
                'form_params'=>[
                    'n'=>$request->get('username'),
                    'p'=>md5($request->get('password')),
                ]
            ]
        );
        if($response
            && $response->getBody()){
            $content=$response->getBody()->getContents();
            if(strlen($content)==13){
                $uid=$content;
                var_dump($uid);
                /** @var Bot $bot */
                $bot= Bot::where('uid','=',$uid)->first();
                if(!$bot){
                    $bot=new Bot();
                    $bot->uid=$uid;
                    $bot->username=$request->get('username');
                    $bot->password=$request->get('password');
                    $bot->token=uniqid('login');
                    $bot->room='5f06817deec1d';
                    $bot->enable=0;
                    $bot->save();
                }
                return new \Http\Responses\JsonResponse([
                    'success'=>true,
                    'error'=>null,
                    'data'=>[
                        'token'=>$bot->token,
                    ],
                ]);
            }else{
                throw new AuthException("密码错误");
            }
        }
        throw new AuthException("无法连接API");
    }
}