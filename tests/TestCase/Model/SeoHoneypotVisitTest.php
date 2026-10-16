<?php

App::uses('SeoAppModel', 'Seo.Model');
App::uses('SeoHoneypotVisit', 'Seo.Model');
App::uses('SeoUtil', 'Seo.Lib');

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
        SeoUtil::$configs = [];
    }

    public function testAdd(): void
    {
        $this->assertTrue($this->SeoHoneypotVisit->add('127.255.253.120'));
    }

    public function testDeleteExpired(): void
    {
        $this->assertEquals(1, $this->SeoHoneypotVisit->find('count'));
        $this->assertTrue($this->SeoHoneypotVisit->deleteExpired());
        $this->assertEquals(0, $this->SeoHoneypotVisit->find('count'));
    }

    public function testDeleteExpiredAfterAdding(): void
    {
        $this->assertEquals(1, $this->SeoHoneypotVisit->find('count'));
        $this->assertTrue($this->SeoHoneypotVisit->add('127.255.253.120'));
        $this->assertTrue($this->SeoHoneypotVisit->add('127.255.253.120'));
        $this->assertTrue($this->SeoHoneypotVisit->deleteExpired());
        $this->assertEquals(2, $this->SeoHoneypotVisit->find('count'));
    }

    public function testDeleteExpiredWithAnAfternoonCutoff(): void
    {
        // The cutoff is yesterday at 15:30, so the 12-hour clock would make it 3:30.
        $cutoff = strtotime('yesterday 15:30');
        SeoUtil::$configs['timeBetweenTriggers'] = time() - $cutoff;
        $this->SeoHoneypotVisit->deleteAll(['1 = 1']);
        $older = date('Y-m-d H:i:s', $cutoff - 1800);
        $newer = date('Y-m-d H:i:s', $cutoff + 1800);
        $this->assertNotFalse($this->SeoHoneypotVisit->saveMany([
            ['ip' => '127.0.0.1', 'created' => $older],
            ['ip' => '127.0.0.1', 'created' => $newer],
        ]));

        $this->assertTrue($this->SeoHoneypotVisit->deleteExpired());
        $this->assertEquals([$newer], array_values($this->SeoHoneypotVisit->find('list', ['fields' => ['id', 'created']])));
    }

    public function testClearKeepsTheVisits(): void
    {
        $this->SeoHoneypotVisit->id = 1;
        $this->assertTrue($this->SeoHoneypotVisit->clear());
        $this->assertFalse($this->SeoHoneypotVisit->id);
        $this->assertEquals(1, $this->SeoHoneypotVisit->find('count'));
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
