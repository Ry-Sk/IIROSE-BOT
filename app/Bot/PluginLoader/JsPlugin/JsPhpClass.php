<?php
namespace Bot\PluginLoader\JsPlugin;

use ReflectionClass;

class JsPhpClass
{
    /** @var \ReflectionClass $class */
    private $class;
    public function __construct($class)
    {
        $this->class=new ReflectionClass($class);
    }

    public function __set($name, $value)
    {
        $this->class->setStaticPropertyValue($name,$value);
    }

    public function __get($name)
    {
        $this->class->getStaticPropertyValue($name);
    }

    public function __call($name,...$parms)
    {
        return $this->class->getMethod($name)->invokeArgs(null,$parms);
    }

    public function newInstance(...$parms){
        return $this->class->newInstanceArgs($parms);
    }
}
