<?php

App::uses('SeoBlacklistsController', 'Seo.Controller');

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
    public function startTest($method): void
    {
        $this->SeoBlacklists = new TestSeoBlacklistsController();
        $this->SeoBlacklists->constructClasses();
    }

    public function endTest($method): void
    {
        unset($this->SeoBlacklists);
        ClassRegistry::flush();
    }
}
