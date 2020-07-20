<?php


namespace Plugin\Math;

use Bot\Event\ChatEvent;
use Bot\Event\CommandEvent;
use Bot\Packets\ChatPacket;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Co\System;
use Console\ErrorFormat;
use File\Path;

class Math extends PhpPlugin
{
    public function onCommand(CommandEvent $event)
    {
        if ($event->sign=='math:maxima') {
            go(function () use ($event) {
                try {
                    $message = implode(' ', $event->input->getArgument('shell'));
                    exec('bash -c "'.addslashes('docker run --rm=true --name "iirose_maxima_'.addslashes($event->sender->getUserId()).'" -m 100M --cpus=0.1 jgoldfar/maxima-docker:debian-latest timeout 5 maxima --batch-string="'.addslashes($message).'"').'" 2>&1', $lines);
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
                    if (strlen($return)<1024) {
                        $event->sender->sendMessage('\\\\\\=' . $return);
                        return;
                    }
                    $storgePath=Path::storge_path('public/plugins/math/'.md5($event->input).'.png');
                    \Co::exec('timeout 5 docker run --rm=true hserr/wkhtmltoimage "'.addslashes('data:text/plain;charset=utf-8;base64,'.base64_encode($return)).'" - > "'.addslashes($storgePath).'"');
                    $event->sender->sendMessage("\n".'喵呜~喵喵搬不动啦，我就丢在这里啦'."\n".'['.url('storge/plugins/math/'.basename($storgePath)).']');
                } catch (\Exception $e) {
                    $event->sender->sendMessage('喵呜~喵喵算不出来啦~太复杂啦');
                    ErrorFormat::dump($e);
                }
            });
        }
    }
}
