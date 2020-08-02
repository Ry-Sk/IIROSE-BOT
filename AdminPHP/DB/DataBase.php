<?php


namespace DB;

use Helper\Config;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Builder;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class DataBase extends Manager
{
    public function __construct()
    {
        parent::__construct();
        $this->addConnection(Config::get('database'));
        $this->setEventDispatcher(new Dispatcher(new Container));
        $this->setAsGlobal();
        $this->bootEloquent();
    }
}
