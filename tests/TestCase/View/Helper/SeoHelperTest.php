<?php

App::uses('SeoHelper', 'Seo.View/Helper');
App::uses('SeoMetaTag', 'Seo.Model');
App::uses('SeoCanonical', 'Seo.Model');
App::uses('HtmlHelper', 'View/Helper');

/**
 * @property SeoHelper $Seo
 */
class SeoHelperTest extends CakeTestCase
{
    public $fixtures = [
        'plugin.seo.seo_meta_tag',
        'plugin.seo.seo_redirect',
        'plugin.seo.seo_uri',
        'plugin.seo.seo_title',
        'plugin.seo.seo_canonical',
        'plugin.seo.seo_a_b_test',
    ];

    public function startTest($method): void
    {
        $View = new View();
        $this->Seo = new SeoHelper($View);
        $this->Seo->Html = new HtmlHelper($View);

        $cacheEngine = SeoUtil::getConfig('cacheEngine');
        if (!empty($cacheEngine)) {
            Cache::clear($cacheEngine);
        }
    }

    public function endTest($method): void
    {
        unset($this->SeometaTagsTag);
        ClassRegistry::flush();
    }

    public function testGetABTestJS(): void
    {
        $this->markTestSkipped();
//        $result = $this->Seo->getABTestJS();
    }

    public function testDnsPrefetch(): void
    {
        //With Default DNS
        $result = $this->Seo->dnsPrefetch();
        $this->assertEquals(null, $result);

        $result = $this->Seo->dnsPrefetch([
            '//www.facebook.com',
            '//use.typekit.net',
        ]);
        $this->assertEquals($result, '<link rel="dns-prefetch" href="//www.facebook.com"><link rel="dns-prefetch" href="//use.typekit.net">');
    }

    public function testCanonical(): void
    {
        $result = $this->Seo->canonical('/example-url');
        $this->assertEquals('<link rel="canonical" href="http://localhost/example-url">', $result);

        $result = $this->Seo->canonical();
        $this->assertEquals('', $result);

        $_SERVER['REQUEST_URI'] = '/canonical';
        $result = $this->Seo->canonical();
        $this->assertEquals('<link rel="canonical" href="http://localhost/new_canonical_link">', $result);
    }

    public function testHoneyPot(): void
    {
        $result = $this->Seo->honeyPot();
        $this->assertTrue(!empty($result));
    }

    public function testmetaTagsTags(): void
    {
        $_SERVER['REQUEST_URI'] = '/uri_for_meta';
        $results = $this->Seo->metaTags();
        $this->assertEquals('<meta name="keywords" content="content_1"/><meta name="description" content="content_2"/>', $results);

        $results = $this->Seo->metaTags(['keywords' => 'ignore me']);
        $this->assertEquals('<meta name="keywords" content="content_1"/><meta name="description" content="content_2"/>', $results);

        $results = $this->Seo->metaTags(['no_ignore' => 'showme']);
        $this->assertEquals('<meta name="keywords" content="content_1"/><meta name="description" content="content_2"/><meta name="no_ignore" content="showme"/>', $results);
    }

    public function testmetaTagsTagsWithHttpEquiv(): void
    {
        $_SERVER['REQUEST_URI'] = '/uri_for_meta_equiv';
        $results = $this->Seo->metaTags();
        $this->assertEquals('<meta http-equiv="content-type" content="text/html"/>', $results);
    }

    public function testMetaTagsTagsWithOutAny(): void
    {
        $_SERVER['REQUEST_URI'] = '/uri_has_not_meta';
        $results = $this->Seo->metaTags();
        $this->assertEquals('', $results);
    }

    public function testMetaTagsTagsWithRegEx(): void
    {
        $_SERVER['REQUEST_URI'] = '/uri_for_meta_reg_ex/this_should_match';
        $results = $this->Seo->metaTags();
        $this->assertEquals('<meta name="default" content="content_default"/><meta name="description_default" content="content_default_2"/>', $results);
    }

    public function testMetaTagsTagsDirectMatchShouldOverwrite(): void
    {
        $_SERVER['REQUEST_URI'] = '/uri_for_meta_reg_ex/this_is_direct_match';
        $results = $this->Seo->metaTags();
        $this->assertEquals('<meta name="direct_match" content="direct_match_content"/>', $results);
    }

    public function testMetaTagsTagsWithWildCard(): void
    {
        $_SERVER['REQUEST_URI'] = '/uri_for_meta_wild_card/wild_card';
        $results = $this->Seo->metaTags();
        $this->assertEquals('<meta name="wild_card_match" content="wild_card_match_content"/>', $results);
    }

    public function testTitleForUri(): void
    {
        $_SERVER['REQUEST_URI'] = '/blah';
        $results = $this->Seo->title();
        $this->assertEquals('<title>Title</title>', $results);
    }

    public function testTitleForUriWithDefault(): void
    {
        $_SERVER['REQUEST_URI'] = '/blahNotDefined';
        $results = $this->Seo->title('default');
        $this->assertEquals('<title>default</title>', $results);
    }
}
