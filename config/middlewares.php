<?php

\Helper\Config::add(
    'middlewares',
    [
        [],
        'app/MiddleWares'=>'\\MiddleWares',
        'AdminPHP/MiddleWares'=>'\\MiddleWares',
    ]);