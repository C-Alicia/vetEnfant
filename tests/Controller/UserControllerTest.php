<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    private $client;

    // Setup: créer un client pour chaque test
    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    // Test de l'index
    public function testIndex()
    {
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur VetEnfant!');
    }

    /* // Test de la récupération de tous les utilisateurs
    public function testGetUsers()
    {
        $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    // Test de la récupération d'un utilisateur par ID
    public function testGetUserById()
    {
        $this->client->request('GET', '/user/1');
        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    // Test de la création d'un utilisateur
    public function testCreateUser()
    {
        $data = [
            'username' => 'john_doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];
        $this->client->request('POST', '/user', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJson($this->client->getResponse()->getContent());
    }

    // Test de la mise à jour d'un utilisateur
    public function testUpdateUser()
    {
        $data = [
            'username' => 'john_doe_updated',
            'email' => 'john_updated@example.com',
            'password' => 'newpassword123',
        ];
        $this->client->request('PUT', '/user/update/1', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($data));

        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }

    // Test de la suppression d'un utilisateur
    public function testDeleteUser()
    {
        $this->client->request('DELETE', '/user/delete/');
        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    } */
}
