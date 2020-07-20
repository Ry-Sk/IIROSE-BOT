<?php
namespace Http;

use Controller\Controller;
use Controller\Controllers\ResourceController;
use DB\DataBase;
use File\Path;
use MiddleWare\MiddleWare;
use Route\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;
use View\View;

class Keneral
{
    public function init()
    {
        new Route();
        new MiddleWare();
        new View();
    }

    public function start()
    {
        echo 'Hello,AdminPHP.';
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function http($request)
    {
        try {
            try {
                $content = new RequestContext(
                    $request->getBaseUrl(),
                    $request->getMethod(),
                    $request->getHost(),
                    $request->getScheme(),
                    $request->getPort(),
                    $request->getPort(),
                    $request->getPathInfo(),
                    $request->getQueryString() ?: ''
                );
                $route = Route::match($content);
            } catch (ResourceNotFoundException $e) {
                $request->setRoute([
                    '_controller' => ResourceController::class,
                    '_method' => 'handle'
                ]);
                $controller_class = ResourceController::class;
                $method = 'handle';
                $response = (MiddleWare::handle($request, function ($request) use ($controller_class, $method) {
                    return Controller::call($controller_class, $method, $request);
                }));
                return $response;
            }
            $request->setRoute($route);
            $controller_class = $route['_controller'];
            $method = $route['_method'];
            $response = (MiddleWare::handle($request, function ($request) use ($controller_class, $method) {
                return Controller::call($controller_class, $method, $request);
            }));
            return $response;
        } catch (\Throwable $e) {
            switch (@$route['_type']) {
                case Route::json:
                    return new Response(
                        ErrorFormat::json($e),
                        400,
                        ['Content-Type'=>Path::get_mine('json')]
                    );
                case Route::html:
                    return new Response(
                        ErrorFormat::http($e),
                        400,
                        ['Content-Type'=>Path::get_mine('html')]
                    );
                default:
                    return new Response(
                        ErrorFormat::text($e),
                        400,
                        ['Content-Type'=>Path::get_mine('txt')]
                    );
            }
        }
    }
}
