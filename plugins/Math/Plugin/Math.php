<?php


namespace Plugin\Math;

use Bot\Event\CommandEvent;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Co\System;
use Console\ErrorFormat;

class Math extends PhpPlugin
{
    public function onCommand(CommandEvent $event)
    {
        if($event->sign=='math:maxima') {
            go(function () use ($event) {
                try {
                    $message = implode(' ', $event->input->getArgument('shell'));
                    $ret = System::exec('bash -c "'.addslashes('docker run --rm --name "iirose_maxima_'.addslashes($event->sender->getUserId()).'" -t -i -m 100M --cpus=0.1 jgoldfar/maxima-docker:debian-latest timeout 5 maxima --batch-string="'.addslashes($message).'"').'" 2>&1');
                    $lines=explode("\n", $ret['output']);
                    $op=false;
                    $return='';
                    foreach ($lines as $line) {
                        if (substr($line, 0, 5)=='(%i1)') {
                            $op=true;
                        }
                        if ($op) {
                            $return.="\n".$line;
                        }
                    }
                    if (!$return) {
                        $event->sender->sendMessage('喵呜~喵喵算不出来啦~太复杂啦');
                        return;
                    }
                    //if (strlen($return)<1024) {
                        $event->sender->sendMessage('\\\\\\=' . $return);
                        return;
                    //}
                    //$token=md5($this->runBot->username.microtime().serialize($input));
                    //Cache::put('bot_docker_echo_'.$token, $return, 600);
                    //$sender->sendMessage("\n喵呜~喵喵搬不动啦，我就丢在这里啦\n".$this->ur(route('cout', ['c'=>$token])));
                } catch (\Exception $e) {
                    $event->sender->sendMessage('喵呜~喵喵算不出来啦~太复杂啦');
                    ErrorFormat::dump($e);
                }
            });
        }
    }
}
