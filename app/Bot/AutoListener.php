<?php


namespace Bot;


use Models\Bot;

trait AutoListener
{
    private $shadow;
    public function registerListeners($shadow=null)
    {
        if(!$shadow){
            $shadow=$this;
        }
        $this->shadow=$shadow;
        $methods=(new \ReflectionClass($this->shadow))->getMethods();
        foreach($methods as $method){
            $parms=$method->getParameters();
            if(count($parms)==1){
                $class=$parms[0]->getClass();
                if($class){
                    $className=$class->getName();
                    if(substr($className,0,10)=='Bot\\Event\\'){
                        $slug=substr($className,10,strlen($className)-15);
                        $className='Bot\\Handler\\'.$slug.'Handler';
                        Bot::$instance->getHandler($className)->addListener(
                            new Listener($this,'@'.$method->getName())
                        );
                    }
                }
            }
        }
    }
    public function __call($name, $arguments)
    {
        // TODO: logos
        /**
         *  @todo todo
         */
        parent::->__call($name, $arguments);
        if(substr($name,0,1)=='@'){
            $method=substr($name,1);
            call_user_func_array([$this->shadow,$method],$arguments);
        }
    }
}