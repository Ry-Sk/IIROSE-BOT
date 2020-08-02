<?php
if (! function_exists('config')) {
    function config($key, $default = null)
    {
        return \Helper\Config::get($key, $default);
    }
}
