<?php
namespace MiddleWare;

use Closure;
use File\File;
use Helper\Config;
use Http\Request;
use ReflectionClass;

class MiddleWare
{
    private static $instance;
    /** @var \MiddleWare\MiddleWares\MiddleWare[] $middlewares */
    private $middlewares=[];
    private $stack=[];
    public function __construct()
    {
        self::$instance=$this;
        $loaders= Config::get('middlewares');
        foreach ($loaders as $name=>$loader) {
            if (is_array($loader)) {
                foreach ($loader as $middleware_class) {
                    /** @var ReflectionClass $middleware_class */
                    $middleware = new $middleware_class();
                    /** @var MiddleWares\MiddleWare $middleware */
                    $this->middlewares[]=$middleware;
                }
            } elseif (is_string($loader)) {
                $files=File::scan_dir_files(ROOT.'/'.$name, false);
                foreach ($files as $file) {
                    $middleware_class=$loader.'\\'.substr($file, 0, strlen($file)-4);
                    /** @var ReflectionClass $middleware_class */
                    $middleware = new $middleware_class();
                    /** @var MiddleWares\MiddleWare $middleware */
                    $this->middlewares[]=$middleware;
                }
            }
        }
        foreach ($this->middlewares as $middleware) {
            $this->stack[]=function ($handler) use ($middleware) {
                return function ($request) use ($middleware,$handler) {
                    return $middleware->hanlde($request, $handler);
                };
            };
        }
    }
    public static function handle(Request $request, $handler)
    {
        foreach (array_reverse(self::$instance->stack) as $key => $fn) {
            $handler = $fn($handler);
        }
        return $handler($request);
    }
}
