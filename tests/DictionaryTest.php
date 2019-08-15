<?php

namespace Tests\KeywordList;

use KeywordList\Dictionary;

/**
 * Class DictionaryTest
 * @package Tests\KeywordList
 */
class DictionaryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Dictionary $dictionary
     */
    protected $dictionary;

    protected function setUp()
    {
        parent::setUp();
        $this->dictionary = new Dictionary(dirname(__DIR__) . '/runtime/dictionary.txt');
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

    protected function tearDown()
    {
        parent::tearDown();
        unlink(dirname(__DIR__) . '/runtime/dictionary.txt');
    }

}
