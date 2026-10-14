<?php

App::uses('SeoUrlsController', 'Seo.Controller');
App::uses('SeoUrl', 'Seo.Model');

class TestSeoUrlsController extends SeoUrlsController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true): ?CakeResponse
    {
        $this->redirectUrl = $url;

        return null;
    }
}

class SeoUrlsControllerTest extends CakeTestCase
{
    public $fixtures = [
        'plugin.seo.seo_url',
    ];

    public function startTest($method): void
    {
        $this->SeoUrls = new TestSeoUrlsController(new CakeRequest('/', false), new CakeResponse());
        $this->SeoUrls->plugin = 'Seo';
        $this->SeoUrls->constructClasses();
    }

    public function endTest($method): void
    {
        unset($this->SeoUrls);
        ClassRegistry::flush();
    }

    public function testConstructClasses(): void
    {
        $this->assertInstanceOf('SeoUrl', $this->SeoUrls->SeoUrl);
    }

    public function testAdminDeleteWithoutId(): void
    {
        $this->SeoUrls->admin_delete();
        $this->assertSame(['action' => 'index'], $this->SeoUrls->redirectUrl);
    }

    public function testAdminView(): void
    {
        $this->SeoUrls->admin_view(3);

        $this->assertSame('/some', $this->SeoUrls->viewVars['seoUri']['SeoUrl']['url']);
        $this->assertSame(3, $this->SeoUrls->viewVars['id']);
    }

    public function testAdminEditLoadsRecord(): void
    {
        $this->SeoUrls->admin_edit(3);

        $this->assertSame('/some', $this->SeoUrls->request->data['SeoUrl']['url']);
        $this->assertSame(3, $this->SeoUrls->viewVars['id']);
    }
}
