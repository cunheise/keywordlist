<?php


namespace KeywordList;


use KeywordList\Dictionary\DictionaryInterface;

/**
 * Class KeywordChecker
 * @package KeywordList
 */
class KeywordChecker implements KeywordCheckerInterface
{
    /**
     * @var DictionaryInterface
     */
    protected $whitelist;
    /**
     * @var DictionaryInterface
     */
    protected $blacklist;
    /**
     * @var string
     */
    protected $illegalKeyword;

    /**
     * KeywordChecker constructor.
     * @param array $options
     */
    public function __construct($options)
    {
        $this->whitelist = $options['whitelist'];
        $this->blacklist = $options['blacklist'];
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
                    $substr = substr($content, $index == 0 ? $index : $index - 1, strlen($keyword) + 2);
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
        $this->blacklist->delete($keywords);
    }

    /**
     * @param array|string $keywords
     * @return void
     */
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

    /**
     * @param array|string $keywords
     * @return void
     */
    public function deleteFromWhiteList($keywords)
    {
        $this->whitelist->delete($keywords);
    }

    /**
     * @return string
     */
    public function getIllegalKeyword()
    {
        return $this->illegalKeyword;
    }

    /**
     * @return array
     */
    public function getWhiteListKeywords()
    {
        return $this->whitelist->getKeywords();
    }

    /**
     * @return array
     */
    public function getBlackListKeywords()
    {
        return $this->blacklist->getKeywords();
    }

    /**
     * @param string $s
     * @return bool
     */
    protected function isAlpha($s)
    {
        return preg_match("/^[a-zA-Z\s]+$/", $s) === 1;
    }

}