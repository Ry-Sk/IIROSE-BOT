<?php
namespace Helper;

use File\File;

class Config
{
    public static $instance;
    private $config=[];
    public function __construct()
    {
        self::$instance=$this;
        foreach (File::scan_dir_files(ROOT.'/config') as $file) {
            require_once $file;
        }
    }
    public static function add($key, $value)
    {
        self::$instance->config[$key]=$value;
    }
    public static function get($key, $default=null)
    {
        return isset(self::$instance->config[$key])
            ? self::$instance->config[$key]
            : $default;
    }
}
