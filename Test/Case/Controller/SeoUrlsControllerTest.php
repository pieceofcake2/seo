<?php

App::uses('SeoUrlsController', 'Seo.Controller');

class TestSeoUrlsController extends SeoUrlsController
{
    public $autoRender = false;

    public function redirect($url, $status = null, $exit = true): void
    {
        $this->redirectUrl = $url;
    }
}

class SeoUrlsControllerTest extends CakeTestCase
{
    public function startTest($method): void
    {
        $this->SeoUrls = new TestSeoUrlsController();
        $this->SeoUrls->constructClasses();
    }

    public function endTest($method): void
    {
        unset($this->SeoUrls);
        ClassRegistry::flush();
    }
}
