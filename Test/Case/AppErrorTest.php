<?php
App::uses('Controller', 'Controller');
App::uses('SeoRedirect', 'Seo.Model');
App::uses('SeoStatusCode', 'Seo.Model');
App::uses('SeoTitle', 'Seo.Model');
App::uses('SeoUri', 'Seo.Model');
App::uses('SeoUrl', 'Seo.Model');
App::uses('SeoCanonical', 'Seo.Model');
App::uses('SeoMetaTag', 'Seo.Model');
App::uses('SeoAppError', 'Seo.Lib/Error');

class AppErrorTest extends CakeTestCase
{
    public $fixtures = [
        'plugin.seo.seo_redirect',
        'plugin.seo.seo_uri',
        'plugin.seo.seo_meta_tag',
        'plugin.seo.seo_title',
        'plugin.seo.seo_status_code',
        'plugin.seo.seo_url',
    ];

    public function startTest($method): void
    {
        $this->AppError = new SeoAppError('ignore', 'ignore', /* test */ true);
    }

    public function testUriToLevenshtein(): void
    {
        Configure::write('Seo.levenshtein', [
            'active' => true,
            'threshold' => 5,
            'cost_add' => 1,
            'cost_change' => 1,
            'cost_delete' => 1,
        ]);
        $_SERVER['REQUEST_URI'] = '/some_url'; // /some is the closest
        $mockController = $this->createMock(Controller::class);
        $mockController->expects($this->once())->method('redirect')->with('/some', 301);
        $this->AppError->controller = $mockController;
        $this->AppError->__uriToLevenshtein();
    }

    public function testUriToRedirectWildCard(): void
    {
        $_SERVER['REQUEST_URI'] = '/blahblahtest'; // /blahblah* will catch this one
        $mockController = $this->createMock(Controller::class);
        $mockController->expects($this->once())->method('redirect')->with('/new', 301);
        $this->AppError->controller = $mockController;
        $this->AppError->__uriToRedirect();
    }

    public function testUriToRedirectWildCardNotMatch(): void
    {
        $_SERVER['REQUEST_URI'] = '/admin/blahblahtest'; // /blahblah* should NOT catch this one
        $mockController = $this->createMock(Controller::class);
        $mockController->expects($this->never())->method('redirect');
        $this->AppError->controller = $mockController;
        $this->AppError->__uriToRedirect();
    }

    public function testUriToStatusCodeGone(): void
    {
        $_SERVER['REQUEST_URI'] = '/status_gone';
        $result = $this->AppError->__uriToStatusCode(true);
        $this->assertEquals('410', $result);
    }

    public function testUriToStatusCodeOk(): void
    {
        $_SERVER['REQUEST_URI'] = '/ok_request';
        $result = $this->AppError->__uriToStatusCode(true);
        $this->assertEquals('', $result);
    }

    public function testUriToRedirectWithCallbackFull(): void
    {
        $_SERVER['REQUEST_URI'] = '/uri';
        $mockController = $this->createMock(Controller::class);
        $mockController->expects($this->once())->method('redirect')->with('/ran_callback', 301);
        $this->AppError->controller = $mockController;
        $this->AppError->__uriToRedirect();
    }

    public function testUriToRedirectWithRegEx(): void
    {
        $_SERVER['REQUEST_URI'] = '/hearing-aids/558-virginia-beach-virginia-va-23454-virginia-audiology?from=sb-tracked:23457';
        $mockController = $this->createMock(Controller::class);
        $mockController->expects($this->once())->method('redirect')->with('/hearing-aids/558-virginia-beach-virginia-va-23454-virginia-audiology', 301);
        $this->AppError->controller = $mockController;
        $this->AppError->__uriToRedirect();
    }

    public function testUriToRedirectWithRegExTwo(): void
    {
        $_SERVER['REQUEST_URI'] = '/some_url_to?from=sb-tracked:2345';
        $mockController = $this->createMock(Controller::class);
        $mockController->expects($this->once())->method('redirect')->with('/some_url_to', 301);
        $this->AppError->controller = $mockController;
        $this->AppError->__uriToRedirect();
    }

    public function testUriToRedirectWithRegExThree(): void
    {
        $_SERVER['REQUEST_URI'] = '/qas/32074-i-told-hearing-aids';
        $mockController = $this->createMock(Controller::class);
        $mockController->expects($this->once())->method('redirect')->with('/questions/32074-i-told-hearing-aids', 301);
        $this->AppError->controller = $mockController;
        $this->AppError->__uriToRedirect();
    }

    public function testUriToRedirect(): void
    {
        $_SERVER['REQUEST_URI'] = '/blah';
        $mockController = $this->createMock(Controller::class);
        $mockController->expects($this->once())->method('redirect')->with('/', 301);
        $this->AppError->controller = $mockController;
        $this->AppError->__uriToRedirect();
    }

    public function testUriToRedirectNotActive(): void
    {
        $_SERVER['REQUEST_URI'] = '/not_active';
        $mockController = $this->createMock(Controller::class);
        $mockController->expects($this->never())->method('redirect');
        $this->AppError->controller = $mockController;
        $this->AppError->__uriToRedirect();
    }

    public function testPriority(): void
    {
        $_SERVER['REQUEST_URI'] = '/blahblahblah';
        $mockController = $this->createMock(Controller::class);
        $mockController->expects($this->once())->method('redirect')->with('/priority', 301);
        $this->AppError->controller = $mockController;
        $this->AppError->__uriToRedirect();
    }
}
