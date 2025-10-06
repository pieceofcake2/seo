<?php

App::uses('SeoAppModel', 'Seo.Model');
App::uses('SeoTitle', 'Seo.Model');

class SeoTitleTest extends CakeTestCase
{
    public $fixtures = [
        'plugin.seo.seo_title',
        'plugin.seo.seo_redirect',
        'plugin.seo.seo_uri',
        'plugin.seo.seo_meta_tag',
        'plugin.seo.seo_status_code',
        'plugin.seo.seo_canonical',
    ];

    public function startTest($method): void
    {
        $this->SeoTitle = ClassRegistry::init('Seo.SeoTitle');
    }

    public function endTest($method): void
    {
        unset($this->SeoTitle);
        ClassRegistry::flush();
    }
}
