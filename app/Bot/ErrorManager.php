<?php


namespace Bot;


class ErrorManager
{
    protected $debug;

    public function __construct($debug = true) {
        $this->debug = $debug;
    }

    public function error() {
        // 禁止显示所有的错误 -1开启所有错误提示
        error_reporting(0);

        // E_ERROR是系统错误，不能被set_error_handler错误处理函数捕获，程序会退出运行
        set_error_handler([$this, 'handle'], E_ALL | E_STRICT);
    }

    public function handle($code, $error, $file, $line, $context) {
        self::formatTrace(debug_backtrace());
    }
    static private function formatTrace($trace){
        foreach($trace as $i => $row){
            $trace[$i]['file']		= isset($row['file']) ? $row['file'] : 'NO FILE';
            $trace[$i]['line']		= isset($row['line']) ? $row['line'] : 'NO LINE';
            $trace[$i]['fileText']	= isset($row['file']) ? self::getFileLines($row['file'], $row['line'] - 4, 10, $row['line']) : '';
            $trace[$i]['function_']	= isset($row['class']) ? $row['class'] . $row['type'] . $row['function'] : $row['function'];
            $trace[$i]['args']		= isset($trace[$i]['args']) ? self::formatArgs($trace[$i]['args']) : [];
        }

        return $trace;
    }
}