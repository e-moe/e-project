<?php

namespace Levi9\EProjectBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isSuccessful());

        $this->assertTrue($crawler->filter('html:contains("Welcome")')->count() > 0);
    }

    /**
     * @depends testIndex
     */
    public function testReset()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('form[reset]')->form();
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testAddPage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/add');

        $this->assertTrue($client->getResponse()->isSuccessful());

        $this->assertTrue($crawler->filter('html:contains("Add new batteries")')->count() > 0);
    }

    /**
     * @dataProvider addProvider
     * @depends testAddPage
     */
    public function testAddBattery($type, $count)
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/add');
        $form = $crawler->selectButton('form[add]')->form();
        $form['form[type]'] = $type;
        $form['form[count]'] = $count;
        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function addProvider()
    {
        return array(
            array('AA',  4),
            array('AAA', 3),
            array('AA',  1),
        );
    }

    /**
     * @depends testIndex
     * @depends testReset
     * @depends testAddBattery
     */
    public function testStatistics()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $trList = $crawler->filter('tr');

        $this->assertEquals(3, $trList->count());

        $trAA = $trList->eq(1);
        $trAAA = $trList->eq(2);

        $this->assertEquals('AA', $trAA->children()->first()->text());
        $this->assertEquals('5', $trAA->children()->last()->text());

        $this->assertEquals('AAA', $trAAA->children()->first()->text());
        $this->assertEquals('3', $trAAA->children()->last()->text());
    }
}
