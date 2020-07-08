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
        \Co\run(function (){
            \Swoole\Runtime::enableCoroutine(SWOOLE_HOOK_ALL);
            new DataBase();
            go(function (){
                /** @var Process[] $procs */
                $procs=[];
                while (true){
                    // A.将数据库转换为BOT LIST
                    /** @var Bot[] $all_bots */
                    $all_bots = Bot::where('enable','=',1)->get();
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
                    // B.关闭不在BOT LIST上的机器人
                    foreach ($procs as $name => $proc) {
                        if (!@$bot_list[$name]) {
                            $proc->kill();
                        }
                    }
                    // C.销毁死亡的机器人
                    foreach ($procs as $name => $proc) {
                        if(!$proc->check()){
                            unset($procs[$name]);
                        }
                    }
                    // D.加载机器人
                    foreach ($bot_list as $name=>$per_bot) {
                        if(!@$procs[$name]){
                            $procs[$name]=new Process($per_bot->id);
                        }
                    }
                    \Co::sleep(5);
                }
            });
        });
        return 0;
    }
}
