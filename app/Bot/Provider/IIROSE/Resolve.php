<?php


namespace Bot\Provider\IIROSE;


use Bot\Extensions\CodeExtension;
use Bot\Handle;
use File\File;
use Logger\Logger;

class Resolve
{
    use CodeExtension;
    /** @var Resolver[] $resolvers */
    protected $resolvers;
    public function __construct()
    {
        $files = File::scan_dir_files(ROOT . '/app/Bot/Provider/IIROSE/Resolver/', false);
        foreach ($files as $file) {
            $resolver_class = '\\Bot\\Provider\\IIROSE\\Resolver\\' . substr($file, 0, strlen($file) - 4);
            /** @var \ReflectionClass $resolver_class */
            $handler = new $resolver_class();
            $this->resolvers[substr($resolver_class, 1)] = $handler;
        }
    }
    public function resolve($message){
        $firstChar = substr($message, 0, 1);
        $explode = self::decode(explode('>', $message));
        $count = count($explode);
        foreach ($this->resolvers as $resolver){
            if($resolver->isPacket($message, $firstChar, $count, $explode)){
                return $resolver->pharse(substr($message,1));
            }
        }
        return null;
    }
}