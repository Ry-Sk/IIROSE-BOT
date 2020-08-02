<?php


namespace Bot\Provider\MiraiApiHttp\Api;


use GuzzleHttp\Client;
use Models\Bot;

abstract class PostApi extends Api
{
    public function push(){
        $client = new Client([
            'http_errors'=>false,
        ]);
        var_dump(json_encode($this));
        $response = $client->post(
            'http://localhost:'
                . Bot::$instance->uid
                . '/'
                . lcfirst(substr(class_basename($this),0,strlen(class_basename($this))-3)),
            [
            'json' => $this
        ]);
        $r=$response->getBody()->getContents();
        var_dump($r);
        return json_decode($r);
    }
}