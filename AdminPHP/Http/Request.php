<?php
namespace Http;

class Request extends \Symfony\Component\HttpFoundation\Request
{
    protected $route;
    public function setRoute($route){
        $this->route=$route;
    }
    public function getRoute($route){
        return $this->route[$route];
    }
    public function getRoutes(){
        return $this->route;
    }
}