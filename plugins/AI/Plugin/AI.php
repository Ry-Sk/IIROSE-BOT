<?php


namespace Plugin\AI;

use Bot\Event\ChatEvent;
use Bot\Packets\ChatPacket;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Console\ErrorFormat;
use GuzzleHttp\Client;

class AI extends PhpPlugin
{
    public function chsadghsjxcvashg(ChatEvent $event)
    {
        if (substr_count($event->message, $this->bot->username)) {
            try {
                $message = $event->message;
                $message = str_replace(' [*'.$this->bot->username.'*] ', '', $message);
                $message = str_replace(' ', '', $message);
                $client = new Client();
                $response = $client->get('http://api.qingyunke.com/api.php', [
                        'query' => [
                            'key' => 'free',
                            'appid' => '0',
                            'msg' => $message
                        ]
                    ]);
                $result = json_decode($response->getBody(), true);
                $return = $result['content'];
                $return = str_replace('{br}', "\n", $return);
                $this->bot->packet(new ChatPacket(' [*' . $event->user_name . '*] ' . $return));
            } catch (\Exception $e) {
                $this->bot->packet(new ChatPacket(' [*' . $event->user_name . '*] 喵呜~喵喵cpu坏啦~喂......不要帮我修啦'));
                ErrorFormat::dump($e);
            }
        }
    }
}
