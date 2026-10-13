<?php

App::uses('SeoAppModel', 'Seo.Model');
App::uses('SeoUrl', 'Seo.Model');

class SeoUrlTest extends CakeTestCase
{
    public $fixtures = [
        'plugin.seo.seo_url',
    ];

    public function startTest($method): void
    {
        $this->SeoUrl = ClassRegistry::init('Seo.SeoUrl');
    }

    public function endTest($method): void
    {
        unset($this->SeoUrl);
        ClassRegistry::flush();
    }

    public function testFindRedirectByRequest(): void
    {
        $this->SeoUrl->settings['active'] = true;
        $this->SeoUrl->settings['cost_add'] = 1;
        $this->SeoUrl->settings['cost_change'] = 1;
        $this->SeoUrl->settings['cost_delete'] = 1;

        $this->SeoUrl->settings['threshold'] = 5;
        $result = $this->SeoUrl->findRedirectByRequest('/some_url');
        $this->assertEquals(['redirect' => '/some', 'shortest' => 4], $result);
        $result = $this->SeoUrl->findRedirectByRequest('/some_other_blah');
        $this->assertEquals(['redirect' => '/some_other_url', 'shortest' => 4], $result);

        $this->SeoUrl->settings['threshold'] = 0;
        $result = $this->SeoUrl->findRedirectByRequest('/some_other');
        $this->assertEquals(['redirect' => '/some_other', 'shortest' => 0], $result);
    }

    public function testLevenshtien(): void
    {
        $request = '/content/Hearing-loss/Treatment';
        $add = 1;
        $change = 2;
        $delete = 3;
        $lev = levenshtein($request, '/content/Hearing-loss/Treatments', $add, $change, $delete);
        $this->assertEquals(1, $lev);

        $lev = levenshtein($request, '/content/articles/Hearing-loss/Protection/30207-Attention-couch-potatoes-time', $add, $change, $delete);
        $this->assertEquals(52, $lev);
    }

    public function testImport(): void
    {
        $this->markTestSkipped();

        $result = $this->SeoUrl->import('/custom-sitemap.xml');
        $this->assertEquals('269', $result);
    }
}
