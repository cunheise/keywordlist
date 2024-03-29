<?php


namespace KeywordList;

/**
 * Interface KeywordCheckerInterface
 * @package KeywordList
 */
interface KeywordCheckerInterface
{
    /**
     * @param string $content
     * @return bool
     */
    public function isValid($content);

    /**
     * @param string|array $keywords
     * @return void
     */
    public function addToBlackList($keywords);

    /**
     * @param string|array $keywords
     * @return void
     */
    public function deleteFromBlackList($keywords);

    /**
     * @param string|array $keywords
     * @return void
     */
    public function addToWhiteList($keywords);

    /**
     * @param string|array $keywords
     * @return void
     */
    public function deleteFromWhiteList($keywords);

    /**
     * @return string
     */
    public function getIllegalKeyword();

    /**
     * @return array
     */
    public function getWhiteListKeywords();

    /**
     * @return array
     */
    public function getBlackListKeywords();
}