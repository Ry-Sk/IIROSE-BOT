<?php

namespace Console;


use Console\Commands\Command;
use Helper\Config;

class Console
{
    /** @var Application $application */
    public $application;
    public function __construct()
    {
        $this->application=new Application(config('app')['name'],config('app')['version']);
        cli_set_process_title(config('app')['name'].config('app')['version']);
        $loaders=Config::get('commands');
        foreach ($loaders as $name=>$loader){
            if(is_array($loader)){
                foreach ($loader as $command_class) {
                    /** @var \ReflectionClass $command_class */
                    $command = new $command_class();
                    /** @var Command $command */
                    $this->application->add($command);
                }
            }elseif(is_string($loader)){
                $files=scandir(ROOT.'/'.$name);
                foreach ($files as $file){
                    if($file == '.' || $file == '..'){
                        continue;
                    }
                    $command_class=$loader.'\\'.substr($file,0,strlen($file)-4);
                    /** @var \ReflectionClass $command_class */
                    $command = new $command_class();
                    /** @var Command $command */
                    $this->application->add($command);
                }
            }
        }
        $this->application->run();
    }
}