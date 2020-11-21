<?php


namespace Bot;

class Process
{
    private $name;
    private $pool;
    private $stdin;
    private $stdout;
    private $stderr;

    public function kill()
    {
        posix_kill(proc_get_status($this->pool)['pid']+1,15);
    }

    public function __construct($command, $name)
    {
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w"),
        );
        $this->pool = proc_open($command, $descriptorspec, $pipes);
        $this->stdin=$pipes[0];
        $this->stdout=$pipes[1];
        $this->stderr=$pipes[2];
        $this->std();
        $this->name=$name;
    }

    public function check()
    {
        if (!$this->pool) {
            return false;
        }
        $status=proc_get_status($this->pool);
        if (!$status) {
            return false;
        }
        if (!@$status['running']) {
            return false;
        }
        return true;
    }
    public function std()
    {
        go(function () {
            while (true) {
                try {
                    $message=@\Co::fread($this->stdout, 4096);
                    if ($message) {
                        $messages=explode("\n", $message);
                        foreach ($messages as $k=>$v) {
                            if (!$v) {
                                unset($messages[$k]);
                            }
                        }
                        $message=$this->name.implode("\n".$this->name, $messages)."\n";
                        echo $message;
                    } else {
                        throw new \Exception();
                    }
                    \Co::sleep(0.1);
                } catch (\Throwable $e) {
                    if (!$this->check()) {
                        return;
                    }
                }
            }
        });
        go(function () {
            while (true) {
                try {
                    $message=@\Co::fread($this->stderr, 4096);
                    if ($message) {
                        $messages=explode("\n", $message);
                        foreach ($messages as $k=>$v) {
                            if (!$v) {
                                unset($messages[$k]);
                            }
                        }
                        $message=$this->name.implode("\n".$this->name, $messages)."\n";
                        echo $message;
                    } else {
                        throw new \Exception();
                    }
                    \Co::sleep(0.1);
                } catch (\Throwable $e) {
                    if (!$this->check()) {
                        return;
                    }
                }
            }
        });
    }
}
