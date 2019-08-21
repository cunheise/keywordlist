<?php


namespace KeywordList\Dictionary;

use InvalidArgumentException;

/**
 * Class AbstractDictionary
 * @package KeywordList\Dictionary
 */
abstract class AbstractDictionary implements DictionaryInterface
{
    /**
     * @param array $keywords
     */
    abstract protected function _add($keywords);

    /**
     * @param array $keywords
     */
    abstract protected function _delete($keywords);

    /**
     * @param string $keyword
     */
    abstract public function exist($keyword);

    /**
     * @return array
     */
    abstract public function getKeywords();

    /**
     * @param array|string $keywords
     */
    public function add($keywords)
    {
        $this->_add($this->convertKeyword($keywords));
    }

    /**
     * @param array|string $keywords
     */
    public function delete($keywords)
    {
        $this->_delete($this->convertKeyword($keywords));
    }

    /**
     * @param string $keyword
     * @return string
     */
    protected function normalize($keyword)
    {
        $keyword = strtolower($keyword);
        foreach ([' ', '　', "\n", "\r"] as $filterItem) {
            $keyword = str_replace($filterItem, '', $keyword);
        }
        return $keyword;
    }

    /**
     * @param array|string $keywords
     * @return array
     */
    protected function convertKeyword($keywords)
    {
        if (is_string($keywords)) {
            $keywords = [$keywords];
        }
        if (!is_array($keywords)) {
            throw new InvalidArgumentException('invalid keywords arguments');
        }
        return $keywords;
    }

}