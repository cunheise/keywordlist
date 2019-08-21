<?php

namespace Tests\KeywordList;

use InvalidArgumentException;
use KeywordList\KeywordChecker;

class KeywordCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var KeywordChecker
     */
    protected $keywordChecker;

    protected function setUp()
    {
        parent::setUp();
        $this->keywordChecker = new KeywordChecker(__DIR__ . '/../runtime');
    }

    public function testIsValid()
    {
        $content = 'this is test';
        $this->keywordChecker->addToBlackList('test');
        $this->assertEquals($this->keywordChecker->isValid($content), false);
        $this->assertEquals($this->keywordChecker->getIllegalKeyword(), 'test');
        $this->keywordChecker->deleteFromBlackList('test');
        $this->keywordChecker->addToBlackList('is');
        $this->assertEquals($this->keywordChecker->isValid($content), false);
        $this->assertEquals($this->keywordChecker->getIllegalKeyword(), 'is');
        $this->assertEquals($this->keywordChecker->isValid('this should be validate'), true);
        $content = 'google';
        $this->keywordChecker->addToBlackList('google');
        $this->assertEquals($this->keywordChecker->isValid($content), false);
        $this->expectException(InvalidArgumentException::class);
        $this->keywordChecker->addToWhiteList('test');
        $this->keywordChecker->deleteFromBlackList('test');
        $this->keywordChecker->addToWhiteList('test');
        $this->expectException(InvalidArgumentException::class);
        $this->keywordChecker->addToBlackList('test');
    }

    protected function tearDown()
    {
        parent::tearDown();
        unlink(dirname(__DIR__) . '/runtime/whitelist.txt');
        unlink(dirname(__DIR__) . '/runtime/blacklist.txt');
    }


}
