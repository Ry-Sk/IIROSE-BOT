<?php


namespace Plugin\RSS;

/**
 * @property string $pubDate
 * @property string $lastBuildDate
 * @property string $docs
 * @property string $description
 * @property string $link
 * @property string $title
 * @property string $webMaster
 * @property string $generator
 * @property string $ttl
 * @property Item[] $item
 */
class Feed
{
    private $feed;
    public function __construct($feed)
    {
        $this->feed=$feed;
    }
    public function __get($name)
    {
        if($name=='item'){
            return $this->feed->item;
        }
        return (string)$this->feed->$name;
    }
}