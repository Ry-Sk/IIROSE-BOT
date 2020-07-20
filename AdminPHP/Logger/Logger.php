<?php
namespace Logger;

class Logger
{
    /** @var Logger $instance */
    private static $instance;
    private $logClosure;
    private $infoClosure;
    private $warnClosure;
    public function __construct()
    {
        self::$instance=$this;
        $this->logClosure=function ($message) {
            echo posix_getpid().'[LOG]' . $message . "\n";
        };
        $this->infoClosure=function ($message) {
            echo posix_getpid().'[32;5;1m[INFO][0m' . $message . "\n";
        };
        $this->warnClosure=function ($message) {
            echo posix_getpid().'[38;5;1m[WARN][0m' . $message . "\n";
        };
    }
    public static function setLog($log)
    {
        self::$instance->logClosure=$log;
    }
    public static function setInfo($info)
    {
        self::$instance->infoClosure=$info;
    }
    public static function setWarn($warn)
    {
        self::$instance->warnClosure=$warn;
    }

    public static function log($message)
    {
        $logClosure=self::$instance->logClosure;
        $logClosure($message);
    }
    public static function info($message)
    {
        $infoClosure=self::$instance->infoClosure;
        $infoClosure($message);
    }
    public static function warn($message)
    {
        $warnClosure=self::$instance->warnClosure;
        $warnClosure($message);
    }
}
