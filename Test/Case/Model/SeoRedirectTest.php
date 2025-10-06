<?php

App::uses('SeoAppModel', 'Seo.Model');
App::uses('SeoRedirect', 'Seo.Model');
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
 * @params SeoRedirect $SeoRedirect
 */
class SeoRedirectTest extends CakeTestCase
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
        $this->SeoRedirect = ClassRegistry::init('Seo.SeoRedirect');
        $seoUri = $this->SeoRedirect->SeoUri;
        $seoUri->Email = $this->getMockBuilder(MockCakeEmail::class)
            ->onlyMethods(['send'])
            ->getMock();
    }

    public function endTest($method): void
    {
        unset($this->SeoRedirect);
        ClassRegistry::flush();
    }

    public function testIsRegEx(): void
    {
        $this->assertTrue($this->SeoRedirect->isRegEx('#(.*)\?from\=sb\-tracked\:(.*)#i'));
        $this->assertTrue($this->SeoRedirect->isRegEx('#(.*)#'));
        $this->assertFalse($this->SeoRedirect->isRegEx('/blah'));
        $this->assertFalse($this->SeoRedirect->isRegEx('/blah#anchor'));
    }

    public function testBeforeSaveShouldSetApproved(): void
    {
        $this->SeoRedirect->SeoUri->Email->expects($this->never())->method('send');

        $this->SeoRedirect->data = [
            'SeoRedirect' => [
              'redirect' => '/',
              'priority' => '5',
              'is_active' => 1,
            ],
            'SeoUri' => [
                'uri' => '/newuri',
            ],
        ];
        $this->assertTrue($this->SeoRedirect->saveAll());
        $result = $this->SeoRedirect->find('last');
        $this->assertTrue($result['SeoUri']['is_approved']);
    }

    public function testBeforeSaveShouldNotSetApprovedOnRegEx(): void
    {
        $this->SeoRedirect->SeoUri->Email->expects($this->once())->method('send');

        $this->SeoRedirect->data = [
            'SeoRedirect' => [
              'redirect' => '/',
              'priority' => '5',
              'is_active' => 1,
            ],
            'SeoUri' => [
                'uri' => '#(somenewregex)#i',
            ],
        ];
        $this->assertTrue($this->SeoRedirect->saveAll());
        $result = $this->SeoRedirect->find('last');
        $this->assertFalse($result['SeoUri']['is_approved']);
    }

    public function testFindRedirectListByPriority(): void
    {
        $results = $this->SeoRedirect->findRedirectListByPriority();
        $this->assertEquals(6, count($results));
    }
}
