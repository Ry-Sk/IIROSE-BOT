<?php


namespace Plugin\RSS;

use Bot\PluginLoader\PhpPlugin\PhpPlugin;
use Console\ErrorFormat;
use DB\DataBase;
use Illuminate\Database\Schema\Blueprint;
use Plugin\RSS\Models\PluginRssPushes;

class RSS extends PhpPlugin
{
    private $handling=false;
    private $lastFinish=0;
    public function __construct($bot, $config, $pluginLoader)
    {
        parent::__construct($bot, $config, $pluginLoader);
        if (!DataBase::schema()->hasTable('plugin_rss_pushes')) {
            DataBase::schema()->create('plugin_rss_pushes', function (Blueprint $table) {
                $table->unsignedInteger('id', true);
                $table->unsignedInteger('bot_id');
                $table->unsignedInteger('guid');
            });
        }
    }
    public function tick()
    {
        parent::tick();
        if (!$this->handling
            && $this->lastFinish < time()-120) {
            go(function () {
                $this->handling=true;
                foreach ($this->config['rss'] as $address) {
                    try {
                        $rss = \Feed::loadRss($address);
                        $rss=new Feed($rss);
                        foreach ($rss->item as $item) {
                            $item=new Item($item);
                            if (!PluginRssPushes
                                ::where('guid', '=', $item->guid)
                                ->where('bot_id', '=', $this->bot->id)->count()) {
                                $pluginRssPushes= new PluginRssPushes();
                                $pluginRssPushes->bot_id=$this->bot->id;
                                $pluginRssPushes->guid=$item->guid;
                                $pluginRssPushes->saveOrFail();
                                foreach($this->bot->provider->getGroupList() as $group){
                                    $this->bot->provider->sendRoomChat($group,$item->getMessage());
                                }
                            }
                        }
                    } catch (\Throwable $e) {
                        echo 'fail during update '.$address."\n";
                        ErrorFormat::dump($e);
                    }
                }
                foreach ($this->config['atom'] as $address) {
                    try {
                        $atom = \Feed::loadAtom($address);
                        $atom=new Feed($atom);
                        foreach ($atom->item as $item) {
                            $item=new Item($item);
                            if (!PluginRssPushes
                                ::where('guid', '=', $item->guid)
                                ->where('bot_id', '=', $this->bot->id)->count()) {
                                $pluginRssPushes= new PluginRssPushes();
                                $pluginRssPushes->bot_id=$this->bot->id;
                                $pluginRssPushes->guid=$item->guid;
                                $pluginRssPushes->saveOrFail();
                                $this->bot->provider->sendRoomChat($group,$item->getMessage());
                            }
                        }
                    } catch (\Throwable $e) {
                        echo 'fail during update '.$address."\n";
                        ErrorFormat::dump($e);
                    }
                }
                $this->lastFinish=time();
                $this->handling=false;
            });
        }
    }
}
