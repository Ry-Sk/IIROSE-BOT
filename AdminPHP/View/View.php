<?php
namespace View;

use File\Path;
use File\File;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Fluent;
use Illuminate\View\ViewServiceProvider;

class View
{
    public function __construct()
    {
        $app = new Container();
        $app['events'] = new Dispatcher();
        $app['config'] = new Fluent();
        $app['files'] = new Filesystem();
        $app['config']['view.paths'] = [ROOT.'/views/'];
        $app['config']['view.compiled'] = ROOT.'/compiled/';
        $serviceProvider = new ViewServiceProvider($app);
        $serviceProvider->register();
        ;
        Facade::setFacadeApplication($app);
        class_alias(\Illuminate\Support\Facades\View::class, 'View');
    }
    public function compile()
    {
        foreach (File::scan_dir(ROOT.'/compiled') as $file) {
            @unlink($file);
        }
        ob_start();
        foreach (File::scan_dir_deep_files(ROOT.'/views') as $file) {
            \Illuminate\Support\Facades\View::file($file)->render();
            ob_clean();
        }
        ob_end_clean();
    }

    /**
     * @param string $view
     * @return \Illuminate\Contracts\View\View
     */
    public static function view($view)
    {
        return \Illuminate\Support\Facades\View::make($view);
    }
}
