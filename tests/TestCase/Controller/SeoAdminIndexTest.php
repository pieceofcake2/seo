<?php

App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');

/**
 * Tests the admin_index action of every Seo admin controller.
 */
class SeoAdminIndexTest extends CakeTestCase
{
    public $fixtures = [
        'plugin.seo.seo_a_b_test',
        'plugin.seo.seo_blacklist',
        'plugin.seo.seo_canonical',
        'plugin.seo.seo_meta_tag',
        'plugin.seo.seo_redirect',
        'plugin.seo.seo_status_code',
        'plugin.seo.seo_title',
        'plugin.seo.seo_uri',
        'plugin.seo.seo_url',
    ];

    public function tearDown(): void
    {
        ClassRegistry::flush();
        parent::tearDown();
    }

    /**
     * @return array<string, array{string, string, string}>
     */
    public static function controllerProvider(): array
    {
        return [
            'SeoABTests' => ['SeoABTestsController', 'SeoABTest', 'seoABTests'],
            'SeoBlacklists' => ['SeoBlacklistsController', 'SeoBlacklist', 'seoBlacklists'],
            'SeoCanonicals' => ['SeoCanonicalsController', 'SeoCanonical', 'seoCanonicals'],
            'SeoMetaTags' => ['SeoMetaTagsController', 'SeoMetaTag', 'seoMetaTags'],
            'SeoRedirects' => ['SeoRedirectsController', 'SeoRedirect', 'seoRedirects'],
            'SeoStatusCodes' => ['SeoStatusCodesController', 'SeoStatusCode', 'seoStatusCodes'],
            'SeoTitles' => ['SeoTitlesController', 'SeoTitle', 'seoTitles'],
            'SeoUris' => ['SeoUrisController', 'SeoUri', 'seoUris'],
            'SeoUrls' => ['SeoUrlsController', 'SeoUrl', 'seoUrls'],
        ];
    }

    /**
     * @dataProvider controllerProvider
     */
    public function testAdminIndex(string $class, string $model, string $viewVar): void
    {
        App::uses($class, 'Seo.Controller');
        $controller = new $class(new CakeRequest('/', false), new CakeResponse());
        $controller->plugin = 'Seo';
        $controller->constructClasses();

        $controller->admin_index();

        $expected = min(20, $controller->{$model}->find('count'));
        $this->assertGreaterThan(0, $expected);
        $this->assertCount($expected, $controller->viewVars[$viewVar]);
        $this->assertNull($controller->viewVars['filter']);
    }

    public function testAdminIndexWithFilter(): void
    {
        App::uses('SeoUrlsController', 'Seo.Controller');
        $controller = new SeoUrlsController(new CakeRequest('/', false), new CakeResponse());
        $controller->plugin = 'Seo';
        $controller->constructClasses();

        $controller->admin_index('some_other');

        $urls = Hash::extract($controller->viewVars['seoUrls'], '{n}.SeoUrl.url');
        sort($urls);
        $this->assertSame(['/some_other', '/some_other_url'], $urls);
        $this->assertSame('some_other', $controller->viewVars['filter']);
    }
}
