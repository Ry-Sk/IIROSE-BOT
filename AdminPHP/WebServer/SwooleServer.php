<?php

namespace WebServer;

use Controller\Controller;
use DB\DataBase;
use File\Path;
use Http\Keneral;
use Http\Request;
use Http\Response;
use MiddleWare\MiddleWare;
use Route\Route;
use Swoole\Http\Server;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class SwooleServer
{
    public function __construct($host, $port)
    {
        $keneral=new Keneral();
        $keneral->init();
        $http = new Server($host, $port);
        $http->on("start", function ($server) use ($keneral) {
            $keneral->start();
        });

        $http->on("request", function ($swoole_request, $swoole_response) use ($keneral) {
            new DataBase();
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
    }
}
