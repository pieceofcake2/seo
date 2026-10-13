<?php

App::uses('SeoBlacklistsController', 'Seo.Controller');
App::uses('SeoBlacklist', 'Seo.Model');

class TestSeoBlacklistsController extends SeoBlacklistsController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true): ?CakeResponse
    {
        $this->redirectUrl = $url;

        return null;
    }
}

class SeoBlacklistsControllerTest extends CakeTestCase
{
    public $fixtures = [
        'plugin.seo.seo_blacklist',
    ];

    public function startTest($method): void
    {
        $this->SeoBlacklists = new TestSeoBlacklistsController();
        $this->SeoBlacklists->plugin = 'Seo';
        $this->SeoBlacklists->constructClasses();
    }

    public function endTest($method): void
    {
        unset($this->SeoBlacklists);
        ClassRegistry::flush();
    }

    public function testConstructClasses(): void
    {
        $this->assertInstanceOf('SeoBlacklist', $this->SeoBlacklists->SeoBlacklist);
    }

    public function testAdminDeleteWithoutId(): void
    {
        $this->SeoBlacklists->admin_delete();
        $this->assertSame(['action' => 'index'], $this->SeoBlacklists->redirectUrl);
    }
}
