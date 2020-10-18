<?php


namespace Plugin\Bot;


use Bot\Event\CommandEvent;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Bot\Provider\IIROSE\Event\JoinEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Models\Sender;
use Bot\Provider\IIROSE\Packets\ChatPacket;
use Bot\Provider\IIROSE\Packets\SourcePacket;
use Console\ErrorFormat;
use GuzzleHttp\Client;

class Bot extends PhpPlugin
{
    public function onCommand(CommandEvent $event)
    {
        if ($event->sign == 'bot:cut'){
            try {#
                IIROSEProvider::$instance->packet(new SourcePacket('{0{"m":"啊！ [*'. $this->bot->username .'*] 传输的媒体信号被不明命令阻断！","mc":"db5a6b","i":"'.uniqid().'"}'));
            } catch (\Exception $e) {
                $event->sender->sendMessage('喵呜~喵喵cpu坏啦~喂......不要帮我修啦');
                ErrorFormat::dump($e);
            }
        }
    }
}