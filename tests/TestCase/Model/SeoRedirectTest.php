<?php

App::uses('SeoAppModel', 'Seo.Model');
App::uses('SeoRedirect', 'Seo.Model');
App::uses('CakeEmail', 'Network/Email');
App::uses('EmailTrait', 'TestSuite');
App::uses('Router', 'Routing');

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
 * @params SeoRedirect $SeoRedirect
 */
class SeoRedirectTest extends CakeTestCase
{
    use EmailTrait;

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
        Router::reload();
    }

    /**
     * Send the notifications with a real CakeEmail and enable the admin prefix routes.
     *
     * @return void
     */
    private function useRealEmailAndAdminRoutes(): void
    {
        $this->SeoRedirect->SeoUri->Email = new CakeEmail();
        Configure::write('Routing.prefixes', ['admin']);
        Router::reload();
    }

    public function testSaveSendsNotificationWithSavedRedirect(): void
    {
        $this->useRealEmailAndAdminRoutes();

        $this->SeoRedirect->clear();
        $result = $this->SeoRedirect->save([
            'SeoRedirect' => ['redirect' => '/new/$1', 'priority' => 10, 'is_active' => 1],
            'SeoUri' => ['uri' => '#/old-(.*)#i'],
        ]);

        $this->assertNotFalse($result);
        $seoUriId = $this->SeoRedirect->field('seo_uri_id');
        $this->assertMailCount(1);
        $this->assertMailSubjectContains('301 Redirect: #/old-(.*)#i to /new/$1 needs approval');
        $this->assertMailContainsHtml('REDIRECT: /new/$1<br />');
        $this->assertMailContainsHtml('PRIORITY: 10<br />');
        $this->assertMailContainsHtml('http://www.example.com/admin/seo/seo_uris/approve/' . $seoUriId . '<br />');
    }

    public function testSaveAllSendsNotificationWithSavedRedirect(): void
    {
        $this->useRealEmailAndAdminRoutes();

        $result = $this->SeoRedirect->saveAll([
            'SeoRedirect' => ['redirect' => '/new/$1', 'priority' => 10, 'is_active' => 1],
            'SeoUri' => ['uri' => '#/old-(.*)#i'],
        ]);

        $this->assertTrue($result);
        $this->assertMailCount(1);
        $this->assertMailContainsHtml('REDIRECT: /new/$1<br />');
        $this->assertMailContainsHtml('PRIORITY: 10<br />');
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
