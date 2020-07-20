<?php


namespace Plugin\RSS;

use File\Path;

/**
 * @property string $title
 * @property string $link
 * @property string $description
 * @property string $pubDate
 * @property string $guid
 * @property string $author
 * @property string $timestamp
 */
class Item
{
    private $item;
    public function __construct($item)
    {
        $this->item=$item;
    }
    public function __get($name)
    {
        return (string)$this->item->$name;
    }
    public function getMessage()
    {
        $storgePath=Path::storge_path('public/plugins/rss/'.md5($this->description).'.png');
        \Co::exec('timeout 5 docker run --rm=true hserr/wkhtmltoimage "'.addslashes('data:text/html;charset=utf-8;base64,'.base64_encode($this->description)).'" - > "'.addslashes($storgePath).'"');
        return "新的RSS推送
标题：$this->title
描述：[".url('storge/plugins/rss/'.basename($storgePath))."]
链接：$this->link
推送时间：$this->pubDate";
    }
}
