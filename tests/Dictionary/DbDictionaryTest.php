<?php

namespace Tests\KeywordList\Dictionary;

use KeywordList\Dictionary\DbDictionary;
use KeywordList\Dictionary\DictionaryInterface;
use Medoo\Medoo;

/**
 * Class DbDictionaryTest
 * @package Tests\KeywordList\Dictionary
 */
class DbDictionaryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Medoo
     */
    protected $db;

    /**
     * @var DictionaryInterface
     */
    protected $dictionary;

    protected function setUp()
    {
        parent::setUp();
        $this->db = new Medoo([
            'database_type' => 'mysql',
            'database_name' => 'dictionary',
            'server' => 'localhost',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'port' => 3306,
//            'prefix' => 'PREFIX_',
        ]);
        $this->db->exec('CREATE TABLE dictionary (value VARCHAR(255) NOT NULL) charset=utf8');
        $this->dictionary = new DbDictionary(['db' => $this->db, 'table' => 'dictionary']);
    }

    public function testAddKeyword()
    {
        $this->dictionary->add('keyword');
        $this->assertEquals($this->dictionary->getKeywords(), ['keyword']);
    }

    public function testAddKeywords()
    {
        $this->dictionary->add(['k1', 'k2']);
        $this->assertEquals($this->dictionary->getKeywords(), ['k1', 'k2']);
    }

    public function testAddUppercaseKeyword()
    {
        $this->dictionary->add('KEYWORD');
        $this->assertEquals($this->dictionary->getKeywords(), ['keyword']);
    }

    public function testSpaceInTheKeyword()
    {
        $this->dictionary->add('key word');
        $this->assertEquals($this->dictionary->getKeywords(), ['keyword']);
    }

    public function testDeleteKeyword()
    {
        $this->dictionary->add('keyword');
        $this->assertEquals(count($this->dictionary->getKeywords()), 1);
        $this->dictionary->delete('keyword');
        $this->assertEquals(count($this->dictionary->getKeywords()), 0);
    }

    public function testDeleteKeywords()
    {
        $this->dictionary->add(['k1', 'k2']);
        $this->assertEquals(count($this->dictionary->getKeywords()), 2);
        $this->dictionary->delete(['k2', 'k1']);
        $this->assertEquals(count($this->dictionary->getKeywords()), 0);
    }

    public function testExist()
    {
        $this->dictionary->add('keyword');
        $this->assertEquals($this->dictionary->exist('keyword'), true);
        $this->assertEquals($this->dictionary->exist('keyword2'), false);
    }

    public function testGetKeywords()
    {
        $this->assertEquals(count($this->dictionary->getKeywords()), 0);
    }

    public function testOrder()
    {
        $this->dictionary->add(['k2', 'k', '中', '中国人']);
        $this->assertEquals($this->dictionary->getKeywords(), ['k', 'k2', '中', '中国人']);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->db->exec('DROP TABLE dictionary');
    }


}
