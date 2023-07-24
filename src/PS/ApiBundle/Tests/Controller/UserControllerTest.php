<?php

namespace PS\ApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');
    }

    public function testPostregister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/postRegister');
    }

}
