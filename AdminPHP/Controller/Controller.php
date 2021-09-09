<?php
namespace Controller;

class Controller
{
    public static function call($controllerClass, $method, $request)
    {
        $controller=new $controllerClass();
        return $controller->$method($request);
    }
}
