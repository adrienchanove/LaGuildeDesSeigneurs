<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlayerControllerTest extends WebTestCase
{
    private $client;
    private $content;
    private static $identifier;


    public function testPlayerRoute(): void
    {
        $this->client->request('GET', '/player');
        $response = $this->client->getResponse();
        $this->isJsonResponse();
    }

    /**
    * Tests create
    */
    public function testCreate(){
        $this->client->request('POST', '/player/create');
        $this->isJsonResponse();
        $this->defineIdentifier();$this->assertIdentifier();}

    public function testDisplay(): void
    {
        $this->client->request('GET', '/player/display/' . self::$identifier);
        $response = $this->client->getResponse();
        $this->isJsonResponse();
        $this->assertIdentifier();
    }

    public function testDisplay404(): void
    {
        $this->client->request('GET', '/player/display/badIdentifier');
        $response = $this->client->getResponse();
        $this->assertError404($response->getStatusCode());
    }

    /**
     * Tests modify
     *//*
    public function testModify()
    {
        $this->client->request('PUT', '/player/modify/' . self::$identifier);
        $response = $this->client->getResponse();
        $this->isJsonResponse();
        $this->assertIdentifier();
    }

    /**
     * Tests delete
     *//*
    public function testDelete()
    {

        $this->client->request('DELETE', '/player/delete/' . self::$identifier);
        $response = $this->client->getResponse();
        $this->isJsonResponse();
    }

    


    /*** Asserts that 'identifier' is present in the Response*/
    public function assertIdentifier()
    {
        $this->assertArrayHasKey('identifier', $this->content);
    }

    /*** Defines identifier*/
    public function defineIdentifier()
    {
        self::$identifier = $this->content['identifier'];
    }










    public function isJsonResponse(): void
    {
        $this->assertResponseIsSuccessful();
        //$this->assertEquals(200,$response->getStatusCode());
        $response = $this->client->getResponse();
        $this->content = json_decode($response->getContent(), true, 50);
        $this->assertTrue($response->headers->contains('content-Type', 'application/json'), $response->headers);
    }

    public function assertError404($statusCode): void
    {
        $this->assertEquals(404, $statusCode);
    }

    public function setUp(): void
    {
        $this->client = static::createClient();
    }
}
