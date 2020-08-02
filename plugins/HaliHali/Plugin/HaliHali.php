<?php


namespace Plugin\HaliHali;

use Bot\Event\CommandEvent;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Bot\Provider\IIROSE\Event\JoinEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Models\Sender;
use Bot\Provider\IIROSE\Packets\ChatPacket;
use Console\ErrorFormat;
use GuzzleHttp\Client;

class HaliHali extends PhpPlugin
{
    public function onCommand(CommandEvent $event)
    {
        if ($event->sign == 'halihali:search') {
            try {
                $client = new Client();
                $response = $client->get('http://testsea.diyiwl.wang/ssszz.php', [
                    'query' => [
                        'top' => 10,
                        'dect' => 0,
                        'q' => $event->input->getArgument('name')
                    ]
                ]);
                $data=$response->getBody()->getContents();
                $data=substr($data,strpos($data,'['));
                $result = json_decode($data, true);
                $event->output->writeln('搜索完成，共有' . count($result) . '个结果');
                $event->output->writeln('=====================================');
                foreach ($result as $per) {
                    $event->output->writeln(explode('/', $per['url'])[2] . ':' . $per['title'].' 更新至'.($per['lianzaijs']?:'1'));
                }
                $event->output->writeln('=====================================');
                $event->output->writeln('使用halihali:play id 集数 点播视频');
            } catch (\Exception $e) {
                $event->sender->sendMessage('喵呜~喵喵cpu坏啦~喂......不要帮我修啦');
                ErrorFormat::dump($e);
            }
        }elseif ($event->sign == 'halihali:play'){
            try {
                $client = new Client();
                $response = $client->get('http://t.mtyee.com/ne2/s'.(int)$event->input->getArgument('id').'.js');
                preg_match_all('(".*?[^\\\\]")',$response->getBody(),$result);
                $event->output->writeln('=====================================');
                foreach ($result[0] as $per) {
                    $parms=explode(',',substr($per,1,strlen($per)-2));
                    if(($event->input->getArgument('number')!=null ? urldecode($parms[2])==$event->input->getArgument('number') : true)
                        && substr($parms[0],0,8)=='https://'
                        && substr($parms[0],strlen($parms[0])-4)!='html') {
                        $event->output->writeln(
                            ($event->input->getArgument('number')!=null ? urldecode($parms[2]).': ' : '')
                            .$parms[0]);
                    }
                }
                $event->output->writeln('=====================================');
                $event->output->writeln('视频提取完成');
                if($event->sender instanceof Sender){
                    $event->sender->sendRawMessage('\\\\\\='.$event->output->fetch());
                }
            } catch (\Exception $e) {
                $event->sender->sendMessage('喵呜~喵喵cpu坏啦~喂......不要帮我修啦');
                ErrorFormat::dump($e);
            }
        }
    }
}