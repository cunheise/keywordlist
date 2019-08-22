<?php


namespace KeywordList\Dictionary;

/**
 * Class Dictionary
 * @package KeywordList
 */
class FileDictionary extends AbstractDictionary
{
    /**
     * @var string
     */
    protected $file;
    /**
     * @var array
     */
    protected $keywords = [];
    /**
     * @var bool
     */
    protected $isModified = false;

    /**
     * Dictionary constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        if (!file_exists($file)) {
            touch($file);
        } else {
            $this->keywords = array_filter(preg_split("/[\r|\n]/", file_get_contents($file)));
        }
        $this->file = $file;
    }

    /**
     * @return array
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param array $keywords
     */
    protected function _add($keywords)
    {
        foreach ($keywords as $keyword) {
            if (!$this->exist($keyword)) {
                array_push($this->keywords, $this->normalize($keyword));
                $this->isModified = true;
            }
        }
        $this->store();
    }

    /**
     * @param array $keywords
     */
    protected function _delete($keywords)
    {
        foreach ($keywords as $keyword) {
            if (($index = array_search($this->normalize($keyword), $this->keywords)) !== false) {
                unset($this->keywords[$index]);
                $this->isModified = true;
            }
        }
        $this->store();
    }

    /**
     * @param string $keyword
     * @return bool
     */
    public function exist($keyword)
    {
        return in_array($this->normalize($keyword), $this->keywords);
    }

    protected function store()
    {
        if ($this->isModified) {
            usort($this->keywords, function ($a, $b) {
                return strlen($a) >= strlen($b);
            });
            file_put_contents($this->file, implode("\n", array_unique($this->keywords)));
        }
    }

}