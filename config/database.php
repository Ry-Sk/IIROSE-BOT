<?php

\Helper\Config::add(
    'database',
    [
        'driver'    => 'sqlite',
        'database'  => \File\Path::storge_path('database.db'),
        'prefix'    => '',
    ]
);
