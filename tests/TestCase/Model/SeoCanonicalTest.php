<?php

App::uses('SeoAppModel', 'Seo.Model');
App::uses('SeoCanonical', 'Seo.Model');
App::uses('CakeEmail', 'Network/Email');

if (!class_exists('MockCakeEmail')) {
    class MockCakeEmail extends CakeEmail
    {
        /**
         * @return array<string>
         */
        public function getTypes(): array
        {
            return $this->_getTypes();
        }
    }
}

class SeoCanonicalTest extends CakeTestCase
{
    /*var $fixtures = array(
        'plugin.seo.seo_meta_tag',
        'plugin.seo.seo_redirect',
        'plugin.seo.seo_uri',
        'plugin.seo.seo_title',
        'plugin.seo.seo_status_code',
        'plugin.seo.seo_canonical',
    );*/

    /**
     * @param mixed $method
     * @return void
     */
    public function startTest($method): void
    {
        $this->SeoCanonical = ClassRegistry::init('Seo.SeoCanonical');
        $seoUri = $this->SeoCanonical->SeoUri;
        $seoUri->Email = $this->getMockBuilder(MockCakeEmail::class)
            ->onlyMethods(['send'])
            ->getMock();
    }

    /**
     * @param mixed $method
     * @return void
     */
    public function endTest($method): void
    {
        unset($this->SeoCanonical);
        ClassRegistry::flush();
    }
}
