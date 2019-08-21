<?php


namespace KeywordList\Dictionary;

/**
 * Interface DictionaryInterface
 * @package KeywordList
 */
interface DictionaryInterface
{
    /**
     * @param string|array $keywords
     */
    public function add($keywords);

    /**
     * @param string|array $keywords
     */
    public function delete($keywords);

    /**
     * @param string $keyword
     * @return bool
     */
    public function exist($keyword);

    /**
     * @return array
     */
    public function getKeywords();
}