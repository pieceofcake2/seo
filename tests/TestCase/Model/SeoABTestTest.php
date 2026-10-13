<?php
App::uses('SeoABTest', 'Seo.Model');

/**
 * SeoABTest Test Case
 */
class SeoABTestTest extends CakeTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.seo.seo_a_b_test',
        'plugin.seo.seo_uri',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->SeoABTest = ClassRegistry::init('Seo.SeoABTest');
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->SeoABTest);

        parent::tearDown();
    }

    public function testRoll(): void
    {
        $this->assertTrue($this->SeoABTest->roll(100));
        $this->assertTrue($this->SeoABTest->roll(['SeoABTest' => ['roll' => '100']]));
        $this->assertFalse($this->SeoABTest->roll(null));
        $this->assertFalse($this->SeoABTest->roll(0));
    }

    public function testIsTestable(): void
    {
        $this->assertTrue($this->SeoABTest->isTestable(null));
        $this->assertTrue($this->SeoABTest->isTestable(['SeoABTest' => ['testable' => '']]));
    }
}
