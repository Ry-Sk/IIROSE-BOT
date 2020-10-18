<?php


namespace Plugin\HaliHali;

use Bot\Event\CommandEvent;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Bot\Provider\IIROSE\Event\JoinEvent;
use Bot\Provider\IIROSE\IIROSEProvider;
use Bot\Provider\IIROSE\Models\Sender;
use Bot\Provider\IIROSE\Packets\ChatPacket;
use Bot\Provider\IIROSE\Packets\SourcePacket;
use Console\ErrorFormat;
use GuzzleHttp\Client;
use Mhor\MediaInfo\MediaInfo;

class HaliHali extends PhpPlugin
{
    public $titleArr=[];
    public $head=null;
    public $defaultInfo=null;
    public $mediaInfo=null;
    function __construct(){
        $this->head=stream_context_create(['http'=>['method'=>'GET','timeout'=>30,'header'=>"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.135 Safari/537.36\r\nConnection: close\r\n"]]);
        // $this->defaultInfo=['Untitled',null,'https://wx3.sinaimg.cn/large/82d8fa61ly1g2hwiflumij20dc0dc3yk'];
        $this->mediaInfo = new MediaInfo();
    }
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
                $event->output->writeln(' ');
                array_splice($this->titleArr,0);
                foreach ($result as $key=>$per) {
                    $id=explode('/', $per['url'])[2];
                    $event->output->writeln($key . ': ' . /*$id . ':' .*/ $per['title'].' 更新至'.($per['lianzaijs']?:'1'));
                    $this->titleArr[$key]=[$per['title'],$per['star'],$per['thumb'],$id];
                }
                // file_put_contents('cache',json_encode(value));
                $event->output->writeln(' ');
                $event->output->writeln('使用halihali:play id 集数 点播视频');
            } catch (\Exception $e) {
                $event->sender->sendMessage('喵呜~喵喵cpu坏啦~喂......不要帮我修啦');
                ErrorFormat::dump($e);
            }
        }elseif ($event->sign == 'halihali:play'){
            try {
                $id=(int)$event->input->getArgument('id');
                if(!isset($this->titleArr[$id])){
                    $event->output->writeln('请先搜索再点播...');
                    return;
                }
                $info=&$this->titleArr[$id];
                $client = new Client();
                $response = $client->get('http://t.mtyee.com/ne2/s'.$info[3].'.js');
                preg_match_all('(".*?[^\\\\]")',$response->getBody(),$result);
                $number=$event->input->getArgument('number');
                foreach ($result[0] as $per) {
                    $parms=explode(',',substr($per,1,strlen($per)-2));
                    if(($number!=null ? urldecode($parms[2])==$number : true)
                        && substr($parms[0],0,8)=='https://'
                        && substr($parms[0],strlen($parms[0])-4)!='html') {
                        $link=$parms[0];
                        break;
          /*              $event->output->writeln(
                            ($event->input->getArgument('number')!=null ? urldecode($parms[2]).': ' : '')
                            .$parms[0]);*/
                    }
                }
                $event->sender->sendRawMessage(isset($link)?'http://r.iirose.com/i/20/1/23/10/4740-BD.gif#e':'https://i0.hdslb.com/bfs/album/162307f1c1761789ec3e03f8456c123aac0ece06.png#e');
                /* $event->output->writeln('解析链接深层1 : '.$link); */
                if(isset($link)/* && $event->sender instanceof Sender*/){
                    if(strpos($link,'.m3u8')===false){
                        $duration=json_decode(json_encode($this->mediaInfo->getInfo($link)),true)['audios'][0]['duration']['milliseconds']/1000;
                    }else{
                        $arr=explode("\n",file_get_contents($link));
                        $link=substr($link,0,($arr[2/*count($arr)-1*/][0]=='/'?strpos($link,'/',8):strrpos($link,'/')+1)).$arr[2/*count($arr)-1*/];
                        /* $event->output->writeln('解析链接深层2 : '.$link); */
                        $m3u8=file_get_contents($link,false,$this->head);
                        if(!$m3u8){
                            $event->output->writeln('视频失效了喵！ 0x001');
                            return;
                        }
                        preg_match_all('/#EXTINF\:(.*?),/',$m3u8,$times);
                        $duration=array_sum($times[1]);
                    }
                    if(!$duration){
                        $event->output->writeln('视频失效了喵！ 0x002');
                        return;
                    }
                    // $event->output->writeln('==================时长 : '.$duration.'===================');
                    // if(isset($this->titleArr[$id])){
                        // $info=&$this->titleArr[$id];
                /*    }else{
                        $info=&$this->defaultInfo;
                        if($info[1]===null){
                            $info[1]=$this->bot->username;
                        }
                    }*/
                    IIROSEProvider::$instance->packet(new SourcePacket('&1'.json_encode(['s'=> substr($link,4),'d'=>$duration,'c'=>substr($info[2],4),'n'=>$info[0],'r'=>$info[1],'b'=>'=1'])));
                    $event->sender->sendRawMessage($info[0] . ' 点播成功啦~'/*$event->output->fetch()*/);
                    array_splice($this->titleArr,0);
                }
            } catch (\Exception $e) {
                $event->sender->sendMessage('喵呜~喵喵cpu坏啦~喂......不要帮我修啦');
                ErrorFormat::dump($e);
            }
        }
    }
}