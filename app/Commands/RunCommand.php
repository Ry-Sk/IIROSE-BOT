<?php


namespace Commands;


use Bot\Process;
use Console\Commands\Command;
use DB\DataBase;
use File\Path;
use Keneral;
use Models\Bot;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Co\run;

class RunCommand extends Command
{
    protected static $defaultName='run';
    protected function configure()
    {
        $this->setProcessTitle('IIROSE-BOT MAIN');
        $this->setDescription('running all bot');
        $this->setHelp('id should be in');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        \Co\run(function (){
            if(!isset($web) || !$web->check()) {
                echo '---:启动web'."\n";
                $web = new Process('php '.ROOT.'/iirosebot server:swoole', 'web:');
            }
            if(!isset($bot) || !$bot->check()) {
                echo '---:启动bot'."\n";
                $bot = new Process('php '.ROOT.'/iirosebot bot','bot:');
            }
            \Co::sleep(1);
        });
        return 0;
    }
}
