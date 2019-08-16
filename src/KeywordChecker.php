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
     * @var array
     */
    protected $illegalKeyword;

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
        $result = true;
        if (!empty($this->whitelist->getKeywords())) {
            $content = str_ireplace($this->whitelist->getKeywords(), '', $content);
        }
        foreach ($this->blacklist->getKeywords() as $keyword) {
            if ($this->isAlpha($keyword)) {
                $index = 0;
                while (($index = stripos($content, $keyword, $index)) !== false) {
                    $substr = substr($content, $index - 1, strlen($keyword) + 2);
                    if (trim($substr) == $keyword) {
                        $this->illegalKeyword = $keyword;
                        $result = false;
                    }
                    $index++;
                }
            } else {
                if (stripos($content, $keyword) !== false) {
                    $this->illegalKeyword = $keyword;
                    $result = false;
                }
            }
        }
        return $result;
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

    public function getIllegalKeyword()
    {
        return $this->illegalKeyword;
    }

    public function isAlpha($s)
    {
        return preg_match("/^[a-zA-Z\s]+$/", $s) === 1;
    }
}