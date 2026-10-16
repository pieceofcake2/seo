<?php

App::uses('SeoAppModel', 'Seo.Model');
App::uses('SeoUri', 'Seo.Model');
App::uses('CakeEmail', 'Network/Email');
App::uses('EmailTrait', 'TestSuite');
App::uses('Router', 'Routing');
App::uses('SeoUtil', 'Seo.Lib');

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
 * @param SeoUri $SeoUri
 */
class SeoUriTest extends CakeTestCase
{
    use EmailTrait;

    public $fixtures = [
        'plugin.seo.seo_uri',
        'plugin.seo.seo_meta_tag',
        'plugin.seo.seo_redirect',
        'plugin.seo.seo_title',
        'plugin.seo.seo_status_code',
        'plugin.seo.seo_canonical',
        'plugin.seo.seo_a_b_test',
    ];

    public function startTest($method): void
    {
        $this->SeoUri = ClassRegistry::init('Seo.SeoUri');
        $this->SeoUri->Email = $this->getMockBuilder(MockCakeEmail::class)
            ->onlyMethods(['send'])
            ->getMock();
    }

    public function endTest($method): void
    {
        unset($this->SeoUri);
        ClassRegistry::flush();
        SeoUtil::$configs = [];
        Router::reload();
    }

    /**
     * Send the notifications with a real CakeEmail and enable the admin prefix routes.
     *
     * @return void
     */
    private function useRealEmailAndAdminRoutes(): void
    {
        $this->SeoUri->Email = new CakeEmail();
        Configure::write('Routing.prefixes', ['admin']);
        Router::reload();
    }

    public function testSendNotificationLinksToApproveAction(): void
    {
        $this->useRealEmailAndAdminRoutes();

        $this->SeoUri->sendNotification(6);

        $this->assertMailCount(1);
        $this->assertMailContainsHtml('http://www.example.com/admin/seo/seo_uris/approve/6');
        $this->assertStringNotContainsString('seo_redirects/approve', implode('', TestEmailTransport::getMessages()[0]->message()));
    }

    public function testSendNotificationLinksToApproveActionWithFullBaseUrl(): void
    {
        $this->useRealEmailAndAdminRoutes();
        SeoUtil::$configs = [
            'approverEmail' => 'approver@example.com',
            'replyEmail' => 'noreply@example.com',
            'parentDomain' => false,
        ];
        Router::fullBaseUrl('https://app.example.org');

        $this->SeoUri->sendNotification(6);

        $this->assertMailContainsHtml('https://app.example.org/admin/seo/seo_uris/approve/6');
    }

    public function testSaveAllSendsNotificationWithSavedRedirect(): void
    {
        $this->useRealEmailAndAdminRoutes();

        $result = $this->SeoUri->saveAll([
            'SeoUri' => ['uri' => '#/old-(.*)#i'],
            'SeoRedirect' => ['redirect' => '/new/$1', 'priority' => 10, 'is_active' => 1],
        ]);

        $this->assertTrue($result);
        $this->assertMailCount(1);
        $this->assertMailSubjectContains('301 Redirect: #/old-(.*)#i to /new/$1 needs approval');
        $this->assertMailContainsHtml('REDIRECT: /new/$1<br />');
        $this->assertMailContainsHtml('PRIORITY: 10<br />');
        $this->assertMailContainsHtml('/admin/seo/seo_uris/approve/' . $this->SeoUri->id);
    }

    public function testSaveAllDoesNotSendNotificationWhenSaveFails(): void
    {
        $this->useRealEmailAndAdminRoutes();

        $result = $this->SeoUri->saveAll([
            'SeoUri' => ['uri' => '#/old-(.*)#i'],
            'SeoRedirect' => ['redirect' => '', 'priority' => 10],
        ]);

        $this->assertFalse($result);
        $this->assertNoMailSent();

        $this->SeoUri->clear();
        $this->SeoUri->save(['SeoUri' => ['uri' => '#/other-(.*)#i']]);
        $this->assertMailCount(1);
    }

    public function testUrlEncode(): void
    {
        $uri = $this->SeoUri->findById(1);

        $this->assertEquals('/blah', $uri['SeoUri']['uri']);
        $this->assertTrue($this->SeoUri->urlEncode(1));

        $result = $this->SeoUri->findById(1);
        $this->assertEquals('/blah', $result['SeoUri']['uri']);

        $uri = $this->SeoUri->findById(14);
        $this->assertEquals('/uri with spaces', $uri['SeoUri']['uri']);
        $this->assertTrue($this->SeoUri->urlEncode(14));
        $result = $this->SeoUri->findById(14);
        $this->assertEquals('/uri%20with%20spaces', $result['SeoUri']['uri']);
    }

    public function testSetApproved(): void
    {
        $this->SeoUri->id = 6;
        $this->assertFalse($this->SeoUri->field('is_approved'));
        $this->SeoUri->setApproved();
        $this->assertTrue($this->SeoUri->field('is_approved'));
    }

    public function testSendNotification(): void
    {
        $this->SeoUri->id = 6;
        $this->SeoUri->Email->expects($this->once())->method('send')->willReturn(true);
        $this->SeoUri->sendNotification();

        $this->assertEquals('301 Redirect: #(.*)#i to / needs approval', $this->SeoUri->Email->subject());
        $this->assertContains('html', $this->SeoUri->Email->getTypes());
    }

    public function testDeleteUriDeletsMeta(): void
    {
        $this->assertTrue($this->SeoUri->SeoMetaTag->hasAny(['id' => 1]));
        $this->assertTrue($this->SeoUri->SeoMetaTag->hasAny(['id' => 2]));
        $this->SeoUri->delete(9);
        $this->assertFalse($this->SeoUri->SeoMetaTag->hasAny(['id' => 1]));
        $this->assertFalse($this->SeoUri->SeoMetaTag->hasAny(['id' => 2]));
    }

    public function testDeleteUriDeleteRedirect(): void
    {
        $this->assertTrue($this->SeoUri->SeoRedirect->hasAny(['id' => 7]));
        $this->SeoUri->delete(7);
        $this->assertFalse($this->SeoUri->SeoRedirect->hasAny(['id' => 7]));
    }
}
