<?php


namespace Plugin\Trpg;

use Bot\Event\ChatEvent;
use Bot\Event\CommandEvent;
use Bot\Packets\ChatPacket;
use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Console\ErrorFormat;

class Trpg extends PhpPlugin
{
    public function onCommand(CommandEvent $event){
        if($event->sign=='trpg:r'){
            try {
                //1d100
                $all = strtoupper($event->input->getArgument('exp'));
                $parms = explode('+', $all);
                //var_dump($parms);
                $o = 0;
                foreach ($parms as $parm) {
                    $pparms = explode('D', $parm);
                    if ($pparms[0] > 100) {
                        return 0;
                    }
                    //var_dump($pparms);
                    for ($i = 0; $i < $pparms[0]; $i++) {
                        $r = $this->bcrandom(1, $pparms[1]);
                        $o = bcadd($o, $r);
                    }
                }
                $event->output->write('唔...结果是' . "\n" . $o);
            }catch (\Throwable $e){
                ErrorFormat::dump($e);
                $event->output->write('表达式错误');
            }
        }
    }
    private function bcrandom($p1,$p2){
        if($p1==$p2){
            return $p1;
        }
        if(bccomp($p1,$p2)==1){
            $min=$p2;
            $max=$p1;
        }else{
            $min=$p1;
            $max=$p2;
        }
        $total=bcsub($max,$min);
        $needLength=strlen($total);
        $randomFile=fopen('/dev/urandom','r');
        $randomMax=bcsub(bcpow(256,$needLength),1);
        //$randomMax=bcsub(bcpow(256,1),1);
        $noAbove=bcsub($randomMax,bcmod($randomMax,$total));
        //var_dump($randomMax);
        while (true){
            $salt=$this->hex2int(bin2hex(fread($randomFile,$needLength)));
            //$salt=hex2int(bin2hex(fread($randomFile,1)));
            if(bccomp($salt,$noAbove)!=1){
                return bcadd($min,bcmod($salt,bcadd($total,1)));
            }
        }
    }
    private function hex2int($hex)
    {
        $len = strlen($hex);
        $dec =0;
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }
        return $dec;
    }
}
