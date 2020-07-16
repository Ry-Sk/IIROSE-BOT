<?php

namespace Commands;
use Console\Commands\Command;
use File\File;
use Phar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use View\View;

class CompileCommand extends Command
{
    /** @var Phar $phar */
    private $phar;
    protected static $defaultName = 'compile';
    protected function configure()
    {
        $this->setProcessTitle('AdminPHP compile');
        $this->setDescription('Compile AdminPHP into phar.');
        $this->setHelp('After compile,you can run AdminPHP with ./admin.php');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        @unlink(ROOT.'/admin.phar');
        (new View())->compile();

        $this->phar = new Phar(ROOT.'/admin.phar', 0, 'admin.phar');
        $this->add_files(ROOT);
        $this->phar->setStub($this->getStub());
        $this->phar->compressFiles(Phar::GZ);
        File::chmod($this->phar->getPath(),'-rwxrwxr-x');

        $output->writeln('好耶～编译成功啦');
        $output->writeln('The compilation is successful');
        $output->writeln($this->phar->getPath());
        return 0;
    }
    private function add_files($dir){
        echo $dir."\n";
        if(file_exists($dir.'/.compile.json')){
            $config=json_decode(file_get_contents($dir.'/.compile.json'));
        }
        $files=scandir($dir);
        foreach ($files as $file) {
            if(($file == '.' || $file == '..' || $file == '.compile.json')
                ||(isset($config) && isset($config->ignore) && in_array($file,$config->ignore))){
                continue;
            }
            if(is_file($dir.'/'.$file)){
                $this->phar->addFile($dir.'/'.$file,substr($dir.'/'.$file,strlen(ROOT)));
            }elseif(is_dir($dir.'/'.$file)){
                $this->phar->addEmptyDir(substr($dir.'/'.$file,strlen(ROOT)));
                $this->add_files($dir.'/'.$file);
            }
        }
    }
    private function getStub()
    {
        $stub = '#!/usr/bin/env php
<?php
Phar::mapPhar(\'admin.phar\');
require \'phar://admin.phar/adminphp\';
__HALT_COMPILER();';
        return $stub;
    }
}
