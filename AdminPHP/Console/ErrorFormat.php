<?php


namespace Console;


use Logger\Logger;

class ErrorFormat
{
    public static function dump(\Throwable $e){
        $o="\n".$e->getMessage();
        $o.="\n".'thrown in '.$e->getFile().' on line '.$e->getLine();
        $o.="\n".'Stack trace:';
        $tracks=array_reverse($e->getTrace());
        $i=count($tracks);
        foreach ($tracks as $track){
            $o.="\n".$i--.str_repeat(' ',4-strlen($i)).$track['class'].$track['type'].$track['function'];
            $o.="\n".'    thrown in '.$track['file'].' on line '.$track['line'];
        }
        Logger::warn($o);
    }
}