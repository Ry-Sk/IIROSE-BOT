<?php
require_once ROOT.'/vendor/autoload.php';
spl_autoload_register(function ($className) {
    $filePaths =[
        ROOT . DIRECTORY_SEPARATOR . 'AdminPHP' . DIRECTORY_SEPARATOR . $className,
        ROOT . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $className,
    ];
    foreach ($filePaths as $filePath) {
        $filePath = str_replace('\\', DIRECTORY_SEPARATOR, $filePath) . '.php';
        if (file_exists($filePath)) {
            require_once $filePath;
        }
    }
});

new Keneral();
