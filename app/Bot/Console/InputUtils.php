<?php


namespace Bot\Console;

use Bot\Event\ChatEvent;
use Bot\Event\PersonChatEvent;
use Bot\Models\Sender;
use Symfony\Component\Console\Input\StringInput;

class InputUtils extends StringInput
{
    /** @var Sender $sender */
    private $sender;
    public function __construct(string $input, $event)
    {
        if ($event instanceof ChatEvent
            || $event instanceof PersonChatEvent) {
            $this->sender=$event->getSender();
        }
        parent::__construct($input);
    }
    /**
     * @return Sender
     */
    public function getSender()
    {
        return $this->sender;
    }
}
