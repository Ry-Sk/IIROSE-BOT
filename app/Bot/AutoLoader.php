<?php


namespace Bot;


use File\Path;

class AutoLoader
{
    private $loaders=[];
    public function __construct()
    {
        spl_autoload_register(function ($className){
            $this->loader($className);
        });
    }
    private function loader($className){
        foreach ($this->loaders as $loader){
            if(substr($className,0,strlen($loader[0]))==$loader[0]){
                $file=Path::get_absolute_path($loader[1].'/'.str_ireplace('\\','/',
                        substr($className,strlen($loader[0]))
                    ).'.php');
                if(file_exists($file)){
                    require_once $file;
                }
            }
        }
    }
    public function add($slug,$path){
        $loader=[$slug,$path];
        $this->loaders[serialize($loader)]=$loader;
        return $this;
    }
}