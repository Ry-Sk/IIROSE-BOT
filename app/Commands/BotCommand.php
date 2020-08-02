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

class BotCommand extends Command
{
    protected static $defaultName='bot';
    protected function configure()
    {
        $this->setProcessTitle('IIROSE-BOT CORE');
        $this->setDescription('running all bot');
        $this->setHelp('id should be in');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        \Co::set(['hook_flags' => SWOOLE_HOOK_ALL | SWOOLE_HOOK_CURL]);
        \Co\run(function () {
            new DataBase();
            go(function () {
                /** @var Process[] $procs */
                $procs=[];
                while (true) {

                    //echo 'checkA';
                    // A.将数据库转换为BOT LIST
                    /** @var Bot[] $all_bots */
                    $all_bots = Bot::where('enable', '!=', 0)->get();
                    /** @var Bot[] $bot_list */
                    $bot_list = [];
                    foreach ($all_bots as $per_bot) {
                        $key=serialize([
                            $per_bot->uid,
                            $per_bot->username,
                            $per_bot->room
                        ]);
                        $bot_list[$key] = $per_bot;
                    }

                    //echo 'checkB';
                    // B.关闭不在BOT LIST上的机器人
                    foreach ($procs as $name => $proc) {
                        if (!@$bot_list[$name]) {
                            $proc->kill();
                        }
                    }

                    //echo 'checkC';
                    // C.销毁死亡的机器人
                    foreach ($procs as $name => $proc) {
                        if (!$proc->check()) {
                            unset($procs[$name]);
                        }
                    }

                    //echo 'checkD';
                    // D.加载机器人
                    foreach ($bot_list as $name=>$per_bot) {
                        if (!@$procs[$name]) {
                            $procs[$name]=new Process('php '.ROOT.'/iirosebot bot:one '.$per_bot->id, '['.$per_bot->username.']:');
                        }
                    }
                    //echo 'checkE';
                    \Co::sleep(5);
                    //echo 'checkF';
                }
            });
        });
        return 0;
    }
}
