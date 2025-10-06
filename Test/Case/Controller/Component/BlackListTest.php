<?php

App::uses('Controller', 'Controller');
App::uses('ComponentCollection', 'Controller');
App::uses('BlackListComponent', 'Seo.Controller/Component');
App::uses('SeoHoneypotVisit', 'Seo.Model');

class TestController extends Controller
{
    public $here;
    public $redirectCalled = false;
    public $redirectArgs = [];

    public function redirect($url, $status = null, $exit = true): ?CakeResponse
    {
        $this->redirectCalled = true;
        $this->redirectArgs = [$url, $status, $exit];
        // Don't actually redirect in tests
        return null;
    }
}

class TestBlacklist extends CakeTestModel
{
    public $name = 'Blacklist';
    public $data = null;
    public $useDbConfig = 'test_suite';
    public $useTable = false;

    public function isBanned(): bool
    {
        return true;
    }
}

class TestHoneyPotVisit extends CakeTestModel
{
    public $name = 'HoneypotVisit';
    public $data = null;
    public $useDbConfig = 'test_suite';
    public $useTable = false;

    public function isTriggered(): bool
    {
        return true;
    }
}

class BlackListTest extends CakeTestCase
{
    public $BlackList = null;

    public function startTest($method): void
    {
        $this->BlackList = new BlackListComponent(new ComponentCollection());
        $this->BlackList->SeoBlacklist = new TestBlacklist();
        $this->BlackList->SeoHoneypotVisit = new TestHoneyPotVisit();
    }

    public function endTest($method): void
    {
        unset($this->BlackList);
    }

    public function testIsBannedRedirect(): void
    {
        $controller = new TestController();
        $controller->here = '/';
        $this->BlackList->Controller = $controller;
        $this->assertTrue($this->BlackList->__isBanned());
        $this->assertTrue($controller->redirectCalled);
    }

    public function testIsBannedOnBannedPage(): void
    {
        $controller = new TestController();
        $controller->here = '/seo/seo_blacklists/banned';
        $this->BlackList->Controller = $controller;
        $this->assertTrue($this->BlackList->__isBanned());
        $this->assertFalse($controller->redirectCalled);
    }

    public function testHandleHoneyPot(): void
    {
        $controller = new TestController();
        $controller->here = '/seo/seo_blacklists/honeypot';
        $this->BlackList->Controller = $controller;
        $this->assertTrue($this->BlackList->__isBanned());
        $this->assertTrue($controller->redirectCalled);
    }
}
