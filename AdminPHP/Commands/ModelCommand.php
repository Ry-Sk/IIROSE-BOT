<?php

namespace Commands;
use Console\Commands\Command;
use DB\DataBase;
use File\File;
use File\Path;
use Illuminate\Support\Str;
use Models\Bot;
use Phar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use View\View;

class ModelCommand extends Command
{
    protected static $defaultName = 'model';
    protected function configure()
    {
        $this->addArgument('model',InputArgument::REQUIRED);
        $this->setProcessTitle('AdminPHP model');
        $this->setDescription('make model from mysql.');
        $this->setHelp('Nerver remove @adminphp');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name=$input->getArgument('model');
        new DataBase();
        $file=Path::app(
            'Models/'.
            strtoupper(substr($name,0,1)).
            substr($name,1).
            '.php');
        $class=strtoupper(substr($name,0,1)).
            substr($name,1);
        if(file_exists($file)){
            $content=file_get_contents($file);
            $before=substr($content,0,strpos($content,"\n".' * @adminphp start'."\n"))."\n".' * @adminphp start';
            $after=substr($content,strpos($content,"\n".' * @adminphp end'."\n"));
        }else{
            $before='<?php
namespace Models;

use Model\Models\Model;

/**
 * Class '.$class.'
 * @package Models
 * @adminphp start';
            $after='
 * @adminphp end
 */
class '.$class.' extends Model
{
}';
        }
        $columns=DataBase::connection()->getSchemaBuilder()->getColumnListing(Str::snake(Str::pluralStudly($name)));
        $o=$before;
        foreach ($columns as $column) {
            $o .= "\n" . ' * @property $' . $column;
        }
        $o.=$after;
        file_put_contents($file,$o);
        return 0;
    }
}
