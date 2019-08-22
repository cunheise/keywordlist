<?php


namespace KeywordList\Dictionary;

use Redis;

/**
 * Class RedisDictionary
 * @package KeywordList\Dictionary
 */
class RedisDictionary extends AbstractDictionary
{
    /**
     * @var Redis
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

    /**
     * @param array $keywords
     */
    protected function _add($keywords)
    {
        foreach ($keywords as $keyword) {
            if (!$this->exist($keyword)) {
                $this->redis->hSet($this->name, $this->getKey($keyword), $this->normalize($keyword));
            }
        }
    }

    /**
     * @param array $keywords
     */
    protected function _delete($keywords)
    {
        foreach ($keywords as $keyword) {
            if ($this->redis->hLen($this->name)) {
                $this->redis->hDel($this->name, $this->getKey($keyword));
            }
        }
    }

    /**
     * @param string $keyword
     * @return bool
     */
    public function exist($keyword)
    {
        return (bool)$this->redis->hExists($this->name, $this->getKey($keyword));
    }

    /**
     * @return array
     */
    public function getKeywords()
    {
        $keywords = $this->redis->hVals($this->name);
        usort($keywords, function ($a, $b) {
            return strlen($a) >= strlen($b);
        });
        return $keywords;
    }

    /**
     * @param string $keyword
     * @return string
     */
    protected function getKey($keyword)
    {
        return md5($this->normalize($keyword));
    }

    public function __destruct()
    {
        $this->redis->close();
    }

}