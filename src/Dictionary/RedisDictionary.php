<?php


namespace KeywordList\Dictionary;


use Redis;

class RedisDictionary extends AbstractDictionary
{
    /**
     * @var \Redis
     */
    protected $redis;

    public function __construct($options)
    {
        if ($options instanceof Redis) {
            $this->redis = $options['redis'];
        } elseif (is_array($options)) {
            $this->redis = new Redis();
        }
    }

    protected function _add($keywords)
    {
        // TODO: Implement _add() method.
    }

    protected function _delete($keywords)
    {
        // TODO: Implement _delete() method.
    }

    public function exist($keyword)
    {
        // TODO: Implement exist() method.
    }

    public function getKeywords()
    {
        // TODO: Implement getKeywords() method.
    }

}