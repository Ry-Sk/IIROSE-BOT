<?php


namespace Plugin\RSS;

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
    public function getMessage(){
        \Co::exec('docker run surnet/alpine-wkhtmltopdf:3.10-0.12.6-full /bin/wkhtmltoimage https://www.baidu.com/ - > test.png');
        return "新的RSS推送
标题：$this->title
描述：$this->description
链接：$this->link
推送时间：$this->pubDate";
    }
}