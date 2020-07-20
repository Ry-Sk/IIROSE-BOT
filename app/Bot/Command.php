<?php


namespace Bot;

use Bot\Console\InputUtils;
use Bot\Event\CommandEvent;
use Bot\Handler\CommandHandler;
use Models\Bot;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'ob:a';
    protected $sign;
    private $config;

    public function __construct($config)
    {
        $this->config=$config;
        $this->sign=$this->config->sign;
        parent::__construct($this->config->sign);
    }

    protected function configure()
    {
        $this->setDescription($this->config->description)
            ->setAliases($this->config->aliases);
        foreach ($this->config->arguments as $argument) {
            $mode=$argument->require ? InputArgument::REQUIRED : InputArgument::OPTIONAL;
            $mode=$argument->array ? $mode|InputArgument::IS_ARRAY : $mode;
            $this->addArgument($argument->name, $mode, $argument->describle, $argument->default);
        }
        foreach ($this->config->options as $option) {
            $mode=$option->require ? InputOption::VALUE_REQUIRED : InputOption::VALUE_OPTIONAL;
            $mode=$option->array ? $mode|InputOption::VALUE_IS_ARRAY : $mode;
            $mode=$option->none ? $mode|InputOption::VALUE_NONE : $mode;
            $this->addOption($option->name, $option->shortcuts, $mode, $option->describle, $option->default);
        }
        foreach ($this->config->usages as $usage) {
            $this->addUsage($usage);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input instanceof InputUtils) {
            return 0;
        }
        Bot::$instance
            ->getHandler(CommandHandler::class)
            ->onEvent(new CommandEvent(
                $this->sign,
                $input->getSender(),
                $input,
                $output
            ));
        return 0;
    }
}
