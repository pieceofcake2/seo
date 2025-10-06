<?php

App::uses('SeoAppModel', 'Seo.Model');
App::uses('SeoStatusCode', 'Seo.Model');
App::uses('CakeEmail', 'Network/Email');

if (!class_exists('MockCakeEmail')) {
    class MockCakeEmail extends CakeEmail
    {
        public function getTypes()
        {
            return $this->_getTypes();
        }
    }
}

/**
 * @property SeoRedirect $SeoRedirect
 */
class SeoStatusCodeTest extends CakeTestCase
{
    public $fixtures = [
        'plugin.seo.seo_redirect',
        'plugin.seo.seo_uri',
        'plugin.seo.seo_meta_tag',
        'plugin.seo.seo_title',
        'plugin.seo.seo_status_code',
        'plugin.seo.seo_canonical',
    ];

    public function startTest($method): void
    {
        $this->SeoStatusCode = ClassRegistry::init('Seo.SeoStatusCode');
        $seoUri = $this->SeoStatusCode->SeoUri;
        $seoUri->Email = $this->getMockBuilder(MockCakeEmail::class)
            ->onlyMethods(['send'])
            ->getMock();
    }

    public function endTest($method): void
    {
        unset($this->SeoStatusCode);
        ClassRegistry::flush();
    }
}
