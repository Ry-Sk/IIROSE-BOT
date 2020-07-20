<?php


namespace Http;

use Logger\Logger;

class ErrorFormat
{
    public static function dump(\Throwable $e)
    {
        $o="\n".'AdminPHP error:  Uncaught '.get_class($e).' in';
        if ($e->getMessage()) {
            $o.="\n".$e->getMessage();
        }
        $o.="\n".'thrown in '.$e->getFile().' on line '.$e->getLine();
        $o.="\n".'Stack trace:';
        $tracks=$e->getTrace();
        $i=count($tracks);
        foreach ($tracks as $track) {
            $o.="\n".$i--.str_repeat(' ', 4-strlen($i)).$track['class'].$track['type'].$track['function'];
            $o.="\n".'    called in '.@$track['file'].' on line '.@$track['line'];
        }
        Logger::warn($o);
        return $o;
    }

    public static function json(\Throwable $e)
    {
        self::dump($e);
        return json_encode([
            'success'=>false,
            'error'=>class_basename($e),
            'message'=>$e->getMessage(),
            '_debug'=>[
                'error'=>get_class($e),
                'message'=>$e->getMessage(),
                'file'=>$e->getFile(),
                'line'=>$e->getLine(),
                'track'=>$e->getTrace(),
            ],
        ]);
    }

    public static function http(\Throwable $e)
    {
        return self::dump($e);
    }
    public static function text(\Throwable $e)
    {
        return self::dump($e);
    }
}
