<?php

namespace Levi9\EProjectBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class DefaultControllerTest extends WebTestCase
{
    /** @var Client */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->client->getContainer()->get('doctrine')->getRepository('Levi9EProjectBundle:Row')->removeAll();
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->assertTrue($crawler->filter('html:contains("Welcome")')->count() > 0);
    }

    /**
     * @depends testIndex
     */
    public function testReset()
    {
        $crawler = $this->client->request('GET', '/');
        $form = $crawler->selectButton('form[reset]')->form();
        $this->client->submit($form);
        $this->assertTrue($this->client->getResponse()->isRedirect());

        $this->client->request('GET', '/');
        $this->assertRegExp('/<td.*?>Empty<\/td>/', $this->client->getResponse()->getContent());

    }

    public function testAddPage()
    {
        $crawler = $this->client->request('GET', '/add');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $this->assertTrue($crawler->filter('html:contains("Add new batteries")')->count() > 0);
    }

    /**
     * Add Battery
     *
     * @param string $type
     * @param int $count
     * @param string $name
     */
    protected function addBattery($type, $count, $name = '')
    {
        $crawler = $this->client->request('GET', '/add');
        $form = $crawler->selectButton('row[add]')->form();
        $form['row[type]'] = $type;
        $form['row[count]'] = $count;
        $form['row[name]'] = $name;
        $this->client->submit($form);
    }

    /**
     * @dataProvider addProvider
     * @depends testAddPage
     */
    public function testAddBattery($type, $count, $name = '')
    {
        $this->addBattery($type, $count, $name);
        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function addProvider()
    {
        return array(
            array('A',   1, 'Nick'),
            array('AA',  2        ),
            array('AAA', 3, 'John'),
        );
    }

    /**
     * @depends testIndex
     * @depends testReset
     * @depends testAddBattery
     */
    public function testStatistics()
    {
        $this->addBattery('AA', 4);
        $this->addBattery('AAA', 3);
        $this->addBattery('AA', 1);

        $this->client->request('GET', '/');
        $this->assertRegExp('/<td>AA<\/td>\s*?<td>5<\/td>/', $this->client->getResponse()->getContent());
        $this->assertRegExp('/<td>AAA<\/td>\s*?<td>3<\/td>/', $this->client->getResponse()->getContent());
    }
}
