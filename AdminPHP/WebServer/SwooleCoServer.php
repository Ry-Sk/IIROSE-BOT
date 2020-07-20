<?php

namespace WebServer;

use DB\DataBase;
use Http\Keneral;
use Http\Request;
use Http\Response;
use Swoole\Coroutine\Http\Server;
use function Co\run;

class SwooleCoServer
{
    public function __construct($host, $port)
    {
        \Co\run(function () use ($host,$port) {
            $keneral=new Keneral();
            $keneral->init();
            $keneral->start();
            new DataBase();
            $http = new Server($host, $port);
            $http->handle('', function ($swoole_request, $swoole_response) use ($keneral) {
                /** @var $swoole_request \Swoole\Http\Request */
                /** @var $swoole_response \Swoole\Http\Response */

                $server=array_change_key_case($swoole_request->server, CASE_UPPER);

                foreach ($swoole_request->header as $k=>$v) {
                    if (!isset($server[$k])) {
                        $server[strtoupper($k)]=$v;
                    }
                }
                $request=new Request(
                    $swoole_request->get?:[],
                    $swoole_request->post?:[],
                    [],
                    $swoole_request->cookie?:[],
                    $swoole_request->files?:[],
                    $server,
                    $swoole_request->rawContent()
                );
                $response=$keneral->http($request);
                foreach ($response->headers->all() as $k=>$v) {
                    foreach ($v as $pv) {
                        $swoole_response->header($k, $pv);
                    }
                }
                $swoole_response->setStatusCode(
                    $response->getStatusCode(),
                    Response::$statusTexts[$response->getStatusCode()]
                );
                $swoole_response->end($response->getContent());
            });
            $http->start();
        });
    }
}
