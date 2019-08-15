<?php


namespace KeywordList;


/**
 * Class KeywordChecker
 * @package KeywordList
 */
class KeywordChecker implements KeywordCheckerInterface
{
    /**
     * @var Dictionary
     */
    protected $whitelist;
    /**
     * @var Dictionary
     */
    protected $blacklist;
    /**
     * @var string
     */
    protected $invalidKeyword;

    /**
     * KeywordChecker constructor.
     * @param string $dir
     */
    public function __construct($dir)
    {
        $dir = trim($dir, '/');
        if (!is_dir($dir)) {
            mkdir($dir, 0644, true);
        }
        $this->whitelist = new Dictionary($dir . '/whitelist.txt');
        $this->blacklist = new Dictionary($dir . '/blacklist.txt');
    }

    /**
     * @param string $content
     * @return bool
     */
    public function isValid($content)
    {
        if (!empty($this->whitelist->getKeywords())) {
            $content = str_ireplace($this->whitelist->getKeywords(), '', $content);
        }
        foreach ($this->blacklist->getKeywords() as $keyword) {
            if (stripos($content, $keyword) !== false) {
                $this->invalidKeyword = $keyword;
                return false;
            }
        }
        return true;
    }

    /**
     * @param array|string $keywords
     * @return mixed|void
     */
    public function addToBlackList($keywords)
    {
        if (is_string($keywords)) {
            $keywords = [$keywords];
        }
        foreach ($keywords as $keyword) {
            if ($this->whitelist->exist($keyword)) {
                throw new \InvalidArgumentException(sprintf('`%s` keyword exist in whitelist', $keyword));
            }
        }
        $this->blacklist->add($keywords);
    }

    /**
     * @param array|string $keywords
     * @return mixed|void
     */
    public function deleteFromBlackList($keywords)
    {
        if (is_string($keywords)) {
            $keywords = [$keywords];
        }
        $this->blacklist->delete($keywords);
    }

    public function addToWhiteList($keywords)
    {
        if (is_string($keywords)) {
            $keywords = [$keywords];
        }
        foreach ($keywords as $keyword) {
            if ($this->blacklist->exist($keyword)) {
                throw new \InvalidArgumentException(sprintf('`%s` keyword exist in blacklist', $keyword));
            }
        }
        $this->whitelist->add($keywords);
    }

    public function deleteFromWhiteList($keywords)
    {
        if (is_string($keywords)) {
            $keywords = [$keywords];
        }
        $this->whitelist->delete($keywords);
    }

    public function getInvalidKeyword()
    {
        return $this->invalidKeyword;
    }

}