<?php

namespace Commands;
use Console\Commands\Command;
use File\File;
use Phar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WebServer\SwooleServer;

class ServerSwooleCommand extends Command
{
    protected static $defaultName = 'server:swoole';
    protected function configure()
    {
        $this->setProcessTitle('AdminPHP swoole server');
        $this->setDescription('Run AdminPHP with swoole.');
        $this->setHelp('You can run AdminPHP without apache/nginx...');
        $this->addOption('host','s',InputOption::VALUE_REQUIRED,'Host you want run in.','0.0.0.0');
        $this->addOption('port','p',InputOption::VALUE_REQUIRED,'Port you want run in.','8008');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        new SwooleServer($input->getOption('host'),$input->getOption('port'));
        return 0;
    }
}
