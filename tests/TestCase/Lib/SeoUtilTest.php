<?php

App::uses('SeoUtil', 'Seo.Lib');

class SeoUtilTest extends CakeTestCase
{
    public $fixtures = ['plugin.seo.seo_blacklist'];

    public function tearDown(): void
    {
        ClassRegistry::flush();
        parent::tearDown();
    }

    public function testLoad(): void
    {
        $this->assertTrue(SeoUtil::loadSeoError());
    }

    public function testIsBanned(): void
    {
        $SeoUtil = new SeoUtil();

        $this->assertTrue($SeoUtil->isBanned('127.255.253.120'));
        $this->assertFalse($SeoUtil->isBanned('10.0.0.1'));
        $this->assertInstanceOf(SeoBlacklist::class, $SeoUtil->SeoBlacklist);
    }

    public function testIsBannedWithoutBlacklistModel(): void
    {
        $SeoUtil = new SeoUtil();
        $SeoUtil->SeoBlacklist = false;

        $this->assertFalse($SeoUtil->isBanned('127.255.253.120'));
    }
}
