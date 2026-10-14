<?php

App::uses('ComponentCollection', 'Controller');
App::uses('ABTestComponent', 'Seo.Controller/Component');

class ABTestComponentTest extends CakeTestCase
{
    public function testReset(): void
    {
        $ABTest = new ABTestComponent(new ComponentCollection());
        $ABTest->test = ['test' => ['SeoABTest' => ['id' => 1]], 'roll' => true];
        CakeSession::write('Seo.ABTests', [1 => true]);

        $ABTest->reset();

        $this->assertNull($ABTest->test);
        $this->assertFalse(CakeSession::check('Seo.ABTests'));
    }
}
