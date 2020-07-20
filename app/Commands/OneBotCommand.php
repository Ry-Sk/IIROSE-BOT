<?php


namespace Commands;

use Console\Commands\Command;
use DB\DataBase;
use Keneral;
use Models\Bot;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OneBotCommand extends Command
{
    protected static $defaultName='bot:one';
    protected function configure()
    {
        $this->addArgument('id', InputArgument::REQUIRED);
        $this->setProcessTitle('IIROSE-BOT');
        $this->setDescription('running bot with id');
        $this->setHelp('id should be in');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setProcessTitle('IIROSE-BOT '.$input->getArgument('id'));
        \Co::set(['hook_flags' => SWOOLE_HOOK_ALL | SWOOLE_HOOK_CURL]);
        \Co\run(function () use ($input) {
            new DataBase();
            $bot=Bot::find($input->getArgument('id'));
            $bot->run();
        });
        return 0;
    }
}
