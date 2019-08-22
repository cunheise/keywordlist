<?php


namespace KeywordList\Dictionary;

use Medoo\Medoo;

/**
 * Class DbDictionary
 * @package KeywordList\Dictionary
 */
class DbDictionary extends AbstractDictionary
{
    /**
     * @var Medoo
     */
    protected $db;
    /**
     * @var string
     */
    protected $table;

    /**
     * DbDictionary constructor.
     * @param array $options
     */
    public function __construct($options)
    {
        $this->db = $options['db'];
        $this->table = $options['table'];
    }

    /**
     * @param array $keywords
     */
    protected function _add($keywords)
    {
        foreach ($keywords as $keyword) {
            if (!$this->exist($keyword)) {
                $this->db->insert($this->table, ['value' => $this->normalize($keyword)]);
            }
        }
    }

    /**
     * @param array $keywords
     */
    protected function _delete($keywords)
    {
        foreach ($keywords as $keyword) {
            $this->db->delete($this->table, ['value' => $this->normalize($keyword)]);
        }
    }

    /**
     * @param string $keyword
     * @return bool
     */
    public function exist($keyword)
    {
        return $this->db->has($this->table, ['value' => $this->normalize($keyword)]);
    }

    /**
     * @return array
     */
    public function getKeywords()
    {
        $rows = $this->db->select($this->table, ['value']);
        usort($rows, function ($a, $b) {
            return strlen($a['value']) >= strlen($b['value']);
        });
        return array_map(function ($row) {
            return $row['value'];
        }, $rows);
    }

}