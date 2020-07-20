<?php


namespace Bot;

use Models\Bot;
use ReflectionClass;

trait AutoListener
{
    public function registerListeners($shadow=null)
    {
        if (!$shadow) {
            $shadow=$this;
        }
        $methods=(new ReflectionClass($shadow))->getMethods();
        foreach ($methods as $method) {
            $parms=$method->getParameters();
            if (count($parms)==1) {
                $class=$parms[0]->getClass();
                if ($class) {
                    $className=$class->getName();
                    if (substr($className, 0, 10)=='Bot\\Event\\') {
                        $slug=substr($className, 10, strlen($className)-15);
                        $className='Bot\\Handler\\'.$slug.'Handler';
                        Bot::$instance->getHandler($className)->addListener(
                            new Listener($this, [$shadow,$method->getName()])
                        );
                    }
                }
            }
        }
    }
}
