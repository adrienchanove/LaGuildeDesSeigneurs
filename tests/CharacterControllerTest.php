<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
    public function testCharacterDisplayRoute(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/character/display');

        $response = $client->getResponse();

        $this->isJsonResponse($response);
    }


    public function testCharacterRoute(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/character');

        $response = $client->getResponse();

        $this->isJsonResponse($response);
    }
    
    public function testDisplay():void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/character/display/2606ee3f0efffc67ef6bb54b2fb7cc3964c6aca8');

        $response = $client->getResponse();

        $this->isJsonResponse($response);

    }



    public function isJsonResponse($response): void
    {
        
        $this->assertResponseIsSuccessful();
        //$this->assertEquals(200,$response->getStatusCode());
        $this->assertTrue($response->headers->contains('content-Type','application/json'), $response->headers);

    }
}
