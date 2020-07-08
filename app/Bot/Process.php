<?php


namespace Bot;


class Process
{
    private $pool;
    private $stdin;
    private $stdout;
    private $stderr;

    public function kill()
    {
        @proc_close($this->pool);
    }

    public function __construct($id)
    {
        $descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w"),
        );
        $this->pool = proc_open('php '.ROOT.'/adminphp bot:one '.$id,$descriptorspec,$pipes);
        $this->stdin=$pipes[0];
        $this->stdout=$pipes[1];
        $this->stderr=$pipes[2];
        $this->std();
    }

    public function check()
    {
        if(!$this->pool){
            return false;
        }
        $status=proc_get_status($this->pool);
        if(!$status){
            return false;
        }
        if(!@$status['running']){
            return false;
        }
        return true;
    }
    public function std(){
        go(function (){
            while (true){
                try {
                    $message=\Co::fread($this->stdout,1024);
                    if($message){
                        echo $message;
                    }else{
                        throw new \Exception();
                    }
                    \Co::sleep(0.1);
                }catch (\Throwable $e){
                    if(!$this->check()){
                        return;
                    }
                }
            }
        });
        go(function (){
            while (true){
                try {
                    $message=@\Co::fread($this->stderr,1024);
                    if($message){
                        echo $message;
                    }else{
                        throw new \Exception();
                    }
                    \Co::sleep(0.1);
                }catch (\Throwable $e){
                    if(!$this->check()){
                        return;
                    }
                }
            }
        });
    }
}