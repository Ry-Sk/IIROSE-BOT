<?php
namespace Route;

use Controllers\WelcomeController;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Route
{
    /** @var Route $instance */
    private static $instance;
    /** @var RouteCollection $routes */
    private $routes;
    public function __construct()
    {
        self::$instance=$this;
        $this->routes = new RouteCollection();
        self::add('tt','/test/{a}',WelcomeController::class,'test');
    }
    public static function add($name,$path,$controller,$method,$methods=['GET'],$host=''){
        self::$instance->routes->add($name,(new \Symfony\Component\Routing\Route(
            $path,
            ['_controller'=>$controller,
                '_method'=>$method
            ],
            [],
            [],
            $host,
            [],
            $methods,
            ''
        )));
    }

    /**
     * @param $path
     * @return array
     * @throws ResourceNotFoundException
     */
    public static function match($path){
        $context = new RequestContext();
        $matcher = new UrlMatcher(self::$instance->routes, $context);
        $parameters = $matcher->match($path);
        return $parameters;
    }
}