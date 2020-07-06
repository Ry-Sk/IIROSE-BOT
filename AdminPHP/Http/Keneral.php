<?php
namespace Http;

use Controller\Controller;
use Controller\Controllers\ResourceController;
use DB\DataBase;
use MiddleWare\MiddleWare;
use Route\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use View\View;

class Keneral
{
    public function init(){
        new Route();
        new MiddleWare();
        new View();
    }

    public function start()
    {
        echo 'Hello,AdminPHP.';
        new DataBase();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function http($request)
    {
        try {
            $route=Route::match($request->getPathInfo());
            $request->setRoute($route);
            $controller_class=$route['_controller'];
            $method=$route['_method'];
            $response=(MiddleWare::handle($request,function ($request)use($controller_class,$method){
                return Controller::call($controller_class,$method,$request);
            }));
            return $response;
        }catch (ResourceNotFoundException $e){
            $request->setRoute([
                '_controller'=>ResourceController::class,
                '_method'=>'handle'
            ]);
            $controller_class=ResourceController::class;
            $method='handle';
            $response=(MiddleWare::handle($request,function ($request)use($controller_class,$method){
                return Controller::call($controller_class,$method,$request);
            }));
            return $response;
        }
    }
}