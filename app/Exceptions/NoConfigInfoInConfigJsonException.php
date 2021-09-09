<?php
namespace Exceptions;

//简单点来说就是...你在config.md解析了配置但是config.json没有这一项
class NoConfigInfoInConfigJsonException extends \Exception
{
}
