<?php

App::uses('ConsoleOutput', 'Console');
App::uses('ConsoleInput', 'Console');
App::uses('SeoRedirectsShell', 'Seo.Console/Command');

class SeoRedirectsShellTest extends CakeTestCase
{
    public $fixtures = [
        'plugin.seo.seo_uri',
        'plugin.seo.seo_redirect',
        'plugin.seo.seo_meta_tag',
        'plugin.seo.seo_title',
        'plugin.seo.seo_status_code',
        'plugin.seo.seo_canonical',
        'plugin.seo.seo_a_b_test',
        'plugin.seo.seo_url',
    ];

    /**
     * @var SeoRedirectsShell&\PHPUnit\Framework\MockObject\MockObject
     */
    public $Shell;

    /**
     * @var array<string>
     */
    public $output = [];

    public function setUp(): void
    {
        parent::setUp();
        $out = $this->getMockBuilder(ConsoleOutput::class)->disableOriginalConstructor()->getMock();
        $in = $this->getMockBuilder(ConsoleInput::class)->disableOriginalConstructor()->getMock();
        $this->Shell = $this->getMockBuilder(SeoRedirectsShell::class)
            ->setConstructorArgs([$out, $out, $in])
            ->onlyMethods(['out'])
            ->getMock();
        $this->output = [];
        $this->Shell->method('out')->willReturnCallback(function ($message = null) {
            $this->output[] = (string)$message;

            return null;
        });
        $this->Shell->initialize();
    }

    public function tearDown(): void
    {
        unset($this->Shell);
        ClassRegistry::flush();
        parent::tearDown();
    }

    public function testAdd(): void
    {
        $this->Shell->args = ['/shell-added-uri', '/shell-target', '50'];
        $this->Shell->add();

        $this->assertContains('Saved.', $this->output);
        $redirect = $this->Shell->SeoRedirect->find('first', [
            'contain' => ['SeoUri'],
            'conditions' => ['SeoRedirect.redirect' => '/shell-target'],
        ]);
        $this->assertSame('/shell-added-uri', $redirect['SeoUri']['uri']);
        $this->assertEquals(50, $redirect['SeoRedirect']['priority']);
    }

    public function testAddWithValidationErrors(): void
    {
        $this->Shell->args = ['/shell-added-uri', '/shell-target', 'not-a-number'];
        $this->expectOutputRegex('/Priorty must be an integer number/');
        $this->Shell->add();

        $this->assertContains('Errors', $this->output);
        $this->assertFalse($this->Shell->SeoRedirect->hasAny(['SeoRedirect.redirect' => '/shell-target']));
    }
}
