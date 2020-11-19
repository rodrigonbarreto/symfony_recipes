<?php

namespace App\Tests\Api;

use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class RecipeTest extends BaseTest
{
    public function testMakeSureNeedAuthentication()
    {
        $client = static::createClient();
        $client->request('GET', '/api/recipes');
        $this->assertEquals('401', $client->getResponse()->getStatusCode());
    }

    public function testMakeSureRecipesAreListed()
    {
        $client = static::createClient();
        $token = $this->login($client);

        $client->request('GET', '/api/recipes', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => "Bearer $token"
        ]);

        self::assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateRecipe()
    {
        $client = static::createClient();
        $token = $this->login($client);
        $client->request('POST', '/api/recipes', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => "Bearer $token"
        ], json_encode([
            'name' => 'Name',
            'description' => 'description',
            'ingredient' => 'ingredient',
            'portions' => 1,
            'preparationTime' => 2,
        ]));

        self::assertEquals(201, $client->getResponse()->getStatusCode());
    }

    private function login(KernelBrowser $client)
    {
        $client->request(
            'POST',
            '/login',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode([
                'email' => 'rodrigonbarreto@gmail.com',
                'password' => '12345678'
            ])
        );

        return json_decode($client->getResponse()->getContent())
            ->access_token;
    }
}
