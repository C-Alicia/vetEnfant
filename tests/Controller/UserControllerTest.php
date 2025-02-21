<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private static $client;

    public static function setUpBeforeClass(): void
    {
        self::$client = static::createClient();
    }

    public function testHomepage()
    {
        self::$client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur VetEnfant!');
    }

    public function testGetUsers()
    {
        self::$client->request('GET', '/users');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testGetUserById()
    {
        self::$client->request('GET', '/user/1');

        $this->assertResponseStatusCodeSame(200);
        $this->assertJson(self::$client->getResponse()->getContent());
    }

    public function testCreateUser()
    {
        self::$client->request(
            'POST',
            '/user',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => 'testuser',
                'email' => 'test@example.com',
                'password' => 'password123'
            ])
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertJson(self::$client->getResponse()->getContent());
    }

    public function testUpdateUser()
    {
        self::$client->request(
            'PUT',
            '/user/1',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => 'updatedUser'])
        );

        $this->assertResponseStatusCodeSame(200);
        $this->assertJson(self::$client->getResponse()->getContent());
    }

    public function testDeleteUser()
    {
        self::$client->request('DELETE', '/user/1');

        $this->assertResponseStatusCodeSame(200);
    }
}
