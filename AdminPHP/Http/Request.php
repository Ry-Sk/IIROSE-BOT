<?php
namespace Http;

use Exceptions\NeedParmsException;

class Request extends \Symfony\Component\HttpFoundation\Request
{
    protected $route;
    public function setRoute($route)
    {
        $this->route=$route;
    }
    public function getRoute($route)
    {
        return $this->route[$route];
    }
    public function getRoutes()
    {
        return $this->route;
    }
    public function getOrFail($key)
    {
        $get=$this->get($key, null);
        if ($get===null) {
            throw new NeedParmsException('No found key '.$key);
        }
        return $get;
    }
}
