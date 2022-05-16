<?php

namespace tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testApiAutorGet(){
        $client = static::createClient();
        $crawler = $client->request('GET','/api/postslist/autor/test/prueba');
        $this->assertEquals(404,$client->getResponse()->getStatusCode());
    }

    public function testApiTituloGet(){
        $client = static::createClient();
        $crawler = $client->request('GET','/api/postslist');
        $this->assertEquals(404,$client->getResponse()->getStatusCode());
    }
}
