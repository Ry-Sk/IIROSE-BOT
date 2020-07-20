<?php
function environment($key, $default)
{
    static $data;
    if (is_null($data)) {
        $data=parse_ini_file(ROOT.'/env.ini');
    }
    return isset($data[$key])?$data[$key]:$default;
}
function url($path)
{
    return environment('URL', 'http://localhost:8008/').$path;
}
