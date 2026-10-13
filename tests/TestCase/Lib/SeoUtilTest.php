<?php

App::uses('SeoUtil', 'Seo.Lib');

class SeoUtilTest extends CakeTestCase
{
    public function testLoad(): void
    {
        $this->assertTrue(SeoUtil::loadSeoError());
    }
}
