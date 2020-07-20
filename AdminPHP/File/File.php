<?php

namespace File;

class File
{
    public static function chmod($file, $permissions)
    {
        $mode = 0;

        if ($permissions[1] == 'r') {
            $mode += 0400;
        }
        if ($permissions[2] == 'w') {
            $mode += 0200;
        }
        if ($permissions[3] == 'x') {
            $mode += 0100;
        } elseif ($permissions[3] == 's') {
            $mode += 04100;
        } elseif ($permissions[3] == 'S') {
            $mode += 04000;
        }

        if ($permissions[4] == 'r') {
            $mode += 040;
        }
        if ($permissions[5] == 'w') {
            $mode += 020;
        }
        if ($permissions[6] == 'x') {
            $mode += 010;
        } elseif ($permissions[6] == 's') {
            $mode += 02010;
        } elseif ($permissions[6] == 'S') {
            $mode += 02000;
        }

        if ($permissions[7] == 'r') {
            $mode += 04;
        }
        if ($permissions[8] == 'w') {
            $mode += 02;
        }
        if ($permissions[9] == 'x') {
            $mode += 01;
        } elseif ($permissions[9] == 't') {
            $mode += 01001;
        } elseif ($permissions[9] == 'T') {
            $mode += 01000;
        }

        return chmod($file, $mode);
    }
    public static function mkdir_deep($dir, $mode=0777)
    {
        if (is_dir($dir)) {
            return true;
        } else {
            return mkdir($dir, 0777, true);
        }
    }
    public static function scan_dir($dir, $absolute=true)
    {
        $dir=Path::formt_dir($dir);
        $files=scandir($dir);
        $result=[];
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $result[]=$absolute ? $dir.$file : $file;
            }
        }
        return $result;
    }
    public static function scan_dir_files($dir, $absolute=true)
    {
        $dir=Path::formt_dir($dir);
        $files=self::scan_dir($dir, true);
        $start=strlen($dir);
        $result=[];
        foreach ($files as $file) {
            if (is_file($file)) {
                $result[]= Path::formt_file($absolute ? $file : substr($file, $start));
            }
        }
        return $result;
    }
    public static function scan_dir_dirs($dir, $absolute=true)
    {
        $dir=Path::formt_dir($dir);
        $files=self::scan_dir($dir, true);
        $start=strlen($dir);
        $result=[];
        foreach ($files as $file) {
            if (is_dir($file)) {
                $result[] = Path::formt_dir($absolute ? $file : substr($file, $start));
            }
        }
        return $result;
    }
    public static function scan_dir_deep($dir, $absolute=true)
    {
        $dir=Path::formt_dir($dir);
        //var_dump($dir);
        $files=self::scan_dir($dir, true);
        $start=strlen($dir);
        $result=[];
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (is_file($file)) {
                $result[]=Path::formt_file($absolute ? $file : substr($file, $start));
            } elseif (is_dir($file)) {
                $result[]=Path::formt_dir($absolute ? $file : substr($file, $start));
                $subfiles=self::scan_dir_deep(Path::formt_dir($file));
                foreach ($subfiles as $subfile) {
                    $result[]=$absolute ? $subfile : substr($subfile, $start);
                }
            }
        }
        return $result;
    }
    public static function scan_dir_deep_files($dir, $absolute=true)
    {
        $dir=Path::formt_dir($dir);
        $files=self::scan_dir_deep($dir, true);
        $start=strlen($dir);
        $result=[];
        foreach ($files as $file) {
            if (is_file($file)) {
                $result[]= Path::formt_file($absolute ? $file : substr($file, $start));
            }
        }
        return $result;
    }
    public static function scan_dir_deep_dirs($dir, $absolute=true)
    {
        $dir=Path::formt_dir($dir);
        $files=self::scan_dir_deep($dir, true);
        $start=strlen($dir);
        $result=[];
        foreach ($files as $file) {
            if (is_dir($file)) {
                $result[]= Path::formt_file($absolute ? $file : substr($file, $start));
            }
        }
        return $result;
    }
}
