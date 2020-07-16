<?php

namespace Commands;
use Console\Commands\Command;
use Console\ErrorFormat;
use File\File;
use Phar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use View\View;

class TestCommand extends Command
{
    protected static $defaultName = 'test';
    protected function configure()
    {
        $this->setProcessTitle('AdminPHP test');
        $this->setDescription('run all files in test dir.');
        $this->setHelp('You can put test code here.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try{
            foreach (File::scan_dir_deep_files(ROOT.'/test/') as $file){
                $output->writeln($file);
                ob_start();
                $result=include $file;
                $stdout=ob_get_contents();
                ob_end_clean();

                ob_start();
                var_dump($result);
                $result=ob_get_contents();
                ob_end_clean();

                $output->writeln('    result:');
                $output->writeln('        '.implode("\n".'        ',explode("\n",$result)));
                $output->writeln('    stdout:');
                $output->writeln('        '.implode("\n".'        ',explode("\n",$stdout)));
            }
        }catch (\Throwable $e){
            ErrorFormat::dump($e);
        }
        return 0;
    }
}
