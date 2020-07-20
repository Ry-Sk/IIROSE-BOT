<?php


namespace File;

use Phar;

class Path
{
    private static $instance;
    private $temp_path;
    public function __construct()
    {
        $this->temp_path=self::formt_dir(sys_get_temp_dir().'/iiroseBot'.posix_getpid());
        self::$instance=$this;
    }
    public function __destruct()
    {
        unlink($this->temp_path);
    }

    public static function temp_path($file='')
    {
        if (!self::$instance) {
            new Path();
        }
        $dir=self::formt_dir(self::$instance->temp_path);
        var_dump(dirname($dir.$file));
        if (!is_dir(dirname($dir.$file))) {
            mkdir(dirname($dir.$file), 0777, true);
        }
        return self::formt_file($dir.$file);
    }
    public static function storge_path($file='')
    {
        $dir=self::formt_dir(self::get_absolute_path(
            dirname(Phar::running(false))?
                dirname(Phar::running(false)).'/storge/':
                ROOT.'/storge/'
        ));
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if (!is_dir(dirname($dir.$file))) {
            mkdir(dirname($dir.$file), 0777, true);
        }
        return self::formt_file($dir.$file);
    }
    public static function public($file='')
    {
        if (!$file) {
            return self::formt_dir(ROOT.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR);
        } else {
            $file=self::formt_file($file);
            return self::formt_file(ROOT.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$file);
        }
    }
    public static function app($file='')
    {
        if (!$file) {
            return self::formt_dir(ROOT.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR);
        } else {
            $file=self::formt_file($file);
            return self::formt_file(ROOT.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.$file);
        }
    }
    public static function get_absolute_path($path)
    {
        $front='';
        if (substr($path, 0, 7)=='phar://') {
            $path=substr($path, 7);
            $front='phar://';
        }
        if (DIRECTORY_SEPARATOR=='\\') {
            $path=str_ireplace('/', '\\', $path);
        }
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) {
            if ('.' == $part) {
                continue;
            }
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        return $front.(substr($path, 0, 1)==DIRECTORY_SEPARATOR?DIRECTORY_SEPARATOR:'').implode(DIRECTORY_SEPARATOR, $absolutes);
    }
    public static function formt_dir($dir)
    {
        $dir=self::get_absolute_path($dir);
        if (DIRECTORY_SEPARATOR=='\\') {
            $dir=str_ireplace('/', '\\', $dir);
            if (substr($dir, strlen($dir)-1)=='\\') {
                return $dir;
            } else {
                return $dir.'\\';
            }
        } else {
            if (substr($dir, strlen($dir)-1)=='/') {
                return $dir;
            } else {
                return $dir.'/';
            }
        }
    }
    public static function formt_file($file)
    {
        $file=self::get_absolute_path($file);
        if (DIRECTORY_SEPARATOR=='\\') {
            $file=str_ireplace('/', '\\', $file);
            if (substr($file, strlen($file)-1)=='\\') {
                return substr($file, 0, strlen($file)-1);
            } else {
                return $file;
            }
        } else {
            if (substr($file, strlen($file)-1)=='/') {
                return substr($file, 0, strlen($file)-1);
            } else {
                return $file;
            }
        }
    }
    public static function get_extension($file)
    {
        return substr(strrchr($file, '.'), 1);
    }
    public static function get_mine($extension)
    {
        $mimes = array(
            'phps' => 1,
            'c' => 'text/plain',
            'cc' => 'text/plain',
            'cpp' => 'text/plain',
            'c++' => 'text/plain',
            'dtd' => 'text/plain',
            'h' => 'text/plain',
            'log' => 'text/plain',
            'rng' => 'text/plain',
            'txt' => 'text/plain',
            'xsd' => 'text/plain',
            'php' => 'text/plain',
            'inc' => 'text/plain',
            'avi' => 'video/avi',
            'bmp' => 'image/bmp',
            'css' => 'text/css',
            'gif' => 'image/gif',
            'htm' => 'text/html',
            'html' => 'text/html',
            'htmls' => 'text/html',
            'ico' => 'image/x-ico',
            'jpe' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'js' => 'application/x-javascript',
            'json' => 'text/json',
            'midi' => 'audio/midi',
            'mid' => 'audio/midi',
            'mod' => 'audio/mod',
            'mov' => 'movie/quicktime',
            'mp3' => 'audio/mp3',
            'mpg' => 'video/mpeg',
            'mpeg' => 'video/mpeg',
            'pdf' => 'application/pdf',
            'png' => 'image/png',
            'swf' => 'application/shockwave-flash',
            'tif' => 'image/tiff',
            'tiff' => 'image/tiff',
            'wav' => 'audio/wav',
            'xbm' => 'image/xbm',
            'xml' => 'text/xml',
        );
        return isset($mimes[$extension]) ? $mimes[$extension] : 'text/plain';
    }
}
