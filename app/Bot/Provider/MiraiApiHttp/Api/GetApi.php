<?php


namespace Bot\Provider\MiraiApiHttp\Api;


use GuzzleHttp\Client;
use Models\Bot;

abstract class GetApi extends Api
{
    public function push(){
        $client = new Client([
            'http_errors'=>false,
        ]);
        $response = $client->get(
            'http://localhost:'
                . Bot::$instance->uid
                . '/'
                . lcfirst(substr(class_basename($this),0,strlen(class_basename($this))-3)),
            [
            'query' => (array)$this
        ]);
        return json_decode($response->getBody());
    }
}