<?php

App::uses('SeoAppModel', 'Seo.Model');
App::uses('SeoMetaTag', 'Seo.Model');
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

/**
 * @property SeoMetaTag $SeoMetaTag
 */
class SeoMetaTagTest extends CakeTestCase
{
    public $fixtures = [
        'plugin.seo.seo_meta_tag',
        'plugin.seo.seo_redirect',
        'plugin.seo.seo_uri',
        'plugin.seo.seo_title',
        'plugin.seo.seo_status_code',
        'plugin.seo.seo_canonical',
    ];

    public function startTest($method): void
    {
        $this->SeoMetaTag = ClassRegistry::init('Seo.SeoMetaTag');
        $seoUri = $this->SeoMetaTag->SeoUri;
        $seoUri->Email = $this->getMockBuilder(MockCakeEmail::class)
            ->onlyMethods(['send'])
            ->getMock();
    }

    public function endTest($method): void
    {
        unset($this->SeoMetaTag);
        ClassRegistry::flush();
    }

    public function testFindAllTagsByUri(): void
    {
        $results = $this->SeoMetaTag->findAllTagsByUri('/uri_for_meta');
        $this->assertEquals(2, count($results));
    }

    public function testFindAllTagsByUriRegEx(): void
    {
        $results = $this->SeoMetaTag->findAllTagsByUri('/uri_for_meta_reg_ex/regex');
        $this->assertEquals(2, count($results));
    }

    public function testFindAllTagsByUriWildCard(): void
    {
        $results = $this->SeoMetaTag->findAllTagsByUri('/uri_for_meta_wild_card/wild');
        $this->assertEquals(1, count($results));
    }

    public function testBeforeSaveShouldLinkToExistinUri(): void
    {
        $this->SeoMetaTag->data = [
            'SeoMetaTag' => [
                'name' => 'New',
                'content' => 'Content',
            ],
            'SeoUri' => [
                'uri' => '/uri_for_meta',
            ],
        ];

        $count = $this->SeoMetaTag->SeoUri->find('count');
        $this->assertTrue($this->SeoMetaTag->save() !== false);
        $this->assertEquals($count, $this->SeoMetaTag->SeoUri->find('count'));
        $results = $this->SeoMetaTag->find('last');
        $this->assertEquals('New', $results['SeoMetaTag']['name']);
        $this->assertEquals('Content', $results['SeoMetaTag']['content']);
        $this->assertEquals(9, $results['SeoMetaTag']['seo_uri_id']);
    }

    public function testBeforeSaveShouldLinkToCreatUri(): void
    {
        $this->SeoMetaTag->data = [
            'SeoMetaTag' => [
                'name' => 'New',
                'content' => 'Content',
            ],
            'SeoUri' => [
                'uri' => '/uri_for_meta_new',
            ],
        ];

        $count = $this->SeoMetaTag->SeoUri->find('count');
        $this->assertTrue($this->SeoMetaTag->save() !== false);
        $this->assertEquals($count + 1, $this->SeoMetaTag->SeoUri->find('count'));

        $results = $this->SeoMetaTag->find('last');
        $this->assertEquals('New', $results['SeoMetaTag']['name']);
        $this->assertEquals('Content', $results['SeoMetaTag']['content']);
    }
}
