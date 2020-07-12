<?php


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminAccountControllerTest extends WebTestCase
{
    public function testConnectAdminWithoutCredentials(): void
    {

        $client = static::createClient();

        $client->request('GET', '/compo-admin/account/profil');

        self::assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
