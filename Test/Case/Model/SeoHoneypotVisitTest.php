<?php

App::uses('SeoAppModel', 'Seo.Model');
App::uses('SeoHoneypotVisit', 'Seo.Model');

class SeoHoneypotVisitTest extends CakeTestCase
{
    public $fixtures = ['plugin.seo.seo_honeypot_visit'];

    public function startTest($method): void
    {
        $this->SeoHoneypotVisit = ClassRegistry::init('Seo.SeoHoneypotVisit');
    }

    public function endTest($method): void
    {
        unset($this->SeoHoneypotVisit);
        ClassRegistry::flush();
    }

    public function testAdd(): void
    {
        $this->assertTrue($this->SeoHoneypotVisit->add('127.255.253.120'));
    }

    public function testClear(): void
    {
        $this->assertEquals(1, $this->SeoHoneypotVisit->find('count'));
        $this->assertTrue($this->SeoHoneypotVisit->clear());
        $this->assertEquals(0, $this->SeoHoneypotVisit->find('count'));
    }

    public function testClearAfterAdding(): void
    {
        $this->assertEquals(1, $this->SeoHoneypotVisit->find('count'));
        $this->assertTrue($this->SeoHoneypotVisit->add('127.255.253.120'));
        $this->assertTrue($this->SeoHoneypotVisit->add('127.255.253.120'));
        $this->assertTrue($this->SeoHoneypotVisit->clear());
        $this->assertEquals(2, $this->SeoHoneypotVisit->find('count'));
    }

    public function testIsTriggered(): void
    {
        $this->assertFalse($this->SeoHoneypotVisit->isTriggered('127.255.253.120'));
        $this->assertTrue($this->SeoHoneypotVisit->add('127.255.253.120'));
        $this->assertTrue($this->SeoHoneypotVisit->add('127.255.253.120'));
        $this->assertTrue($this->SeoHoneypotVisit->add('127.255.253.120'));
        $this->assertTrue($this->SeoHoneypotVisit->add('127.255.253.120'));
        $this->assertTrue($this->SeoHoneypotVisit->add('127.255.253.120'));
        $this->assertTrue($this->SeoHoneypotVisit->add('127.255.253.120'));
        $this->assertTrue($this->SeoHoneypotVisit->isTriggered('127.255.253.120'));
    }
}
