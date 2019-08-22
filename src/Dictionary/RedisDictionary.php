<?php


namespace KeywordList\Dictionary;


use Predis\ClientInterface;

class RedisDictionary extends AbstractDictionary
{
    /**
     * @var ClientInterface
     */
    protected $redis;
    /**
     * @var string
     */
    protected $name;

    /**
     * RedisDictionary constructor.
     * @param array $options
     */
    public function __construct($options)
    {
        $this->redis = $options['redis'];
        $this->name = $options['name'];
    }

    protected function _add($keywords)
    {
        foreach ($keywords as $keyword) {
            if (!$this->exist($keyword)) {
                $this->redis->hset($this->name, $this->getKey($keyword), $this->normalize($keyword));
            }
        }
    }

    protected function _delete($keywords)
    {
        foreach ($keywords as $keyword) {
            if ($this->redis->hlen($this->name)) {
                $this->redis->hdel($this->name, $this->getKey($keyword));
            }
        }
    }

    public function exist($keyword)
    {
        return (bool)$this->redis->hexists($this->name, $this->getKey($keyword));
    }

    public function getKeywords()
    {
        $keywords = $this->redis->hvals($this->name);
        usort($keywords, function ($a, $b) {
            return strlen($a) >= strlen($b);
        });
        return $keywords;
    }

    protected function getKey($keyword)
    {
        return md5($this->normalize($keyword));
    }

}