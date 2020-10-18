<?php


namespace Plugin\Hitokoto;

use Bot\Event\CommandEvent;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Bot\Provider\IIROSE\Event\JoinEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Models\Sender;
use Bot\Provider\IIROSE\Packets\ChatPacket;
use Bot\Provider\IIROSE\Packets\BoardCastPacket;
use Console\ErrorFormat;
use GuzzleHttp\Client;

class Hitokoto extends PhpPlugin
{
    public function onCommand(CommandEvent $event)
    {
        if ($event->sign == 'hitokoto:all'){
            try {
                $client = new Client();
                $response = $client->get('https://v1.hitokoto.cn/?encode=json&c=a&c=b&c=d&c=e&c=j&c=f');
                $data=$response->getBody()->getContents();
                $result = json_decode($data, true);
                $event->output->writeln($result['hitokoto'] . '  —— ' . $result['from']);
            } catch (\Exception $e) {
                $event->sender->sendMessage('喵呜~喵喵cpu坏啦~喂......不要帮我修啦');
                ErrorFormat::dump($e);
            }
        }
    }
}