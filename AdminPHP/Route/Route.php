<?php
namespace Route;

use Controllers\WelcomeController;
use File\File;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Route
{
    const html = 'html';
    const json = 'json';
    /** @var Route $instance */
    private static $instance;
    /** @var RouteCollection $routes */
    private $routes;
    public function __construct()
    {
        self::$instance=$this;
        $this->routes = new RouteCollection();
        foreach (File::scan_dir_files(ROOT.'/routes') as $file) {
            require_once $file;
        }
    }
    public static function add($name, $path, $controller, $method, $methods=['GET'], $type=self::html, $host='')
    {
        self::$instance->routes->add($name, (new \Symfony\Component\Routing\Route(
            $path,
            ['_controller'=>$controller,
                '_method'=>$method,
                '_type'=>$type
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
    public static function match($context)
    {
        $matcher = new UrlMatcher(self::$instance->routes, $context);
        $parameters = $matcher->match($context->getPathInfo());
        return $parameters;
    }
}
