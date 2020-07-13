<?php


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminAccountControllerTest extends WebTestCase
{
    //tests page inscription
    public function testGoOnAdminInscriptionPageWith200Return(): void
    {
        $client = static::createClient();

        $client->request('GET', '/compo-admin/account/inscription');

        self::assertEquals(200, $client->getResponse()->getStatusCode());
        //self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testTitleOnRegisterPage(): void
    {
        $client = static::createClient();

        $client->request('GET', '/compo-admin/account/inscription');

        self::assertSelectorTextContains('h1.text-center.blue-title',"Formulaire d'inscription");
    }

    //tests page profil
    public function testGoAdminProfilWithoutCredentialsRedirectWith302(): void
    {

        $client = static::createClient();

        $client->request('GET', '/compo-admin/account/profil');

        self::assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testGoAdminProfilWithoutCredentialsRedirectToAdminLogin(): void
    {

        $client = static::createClient();

        $client->request('GET', '/compo-admin/account/profil');

        self::assertResponseRedirects('http://localhost/compo-admin/account/admin-connexion');
    }

    //tests page password update
    public function testGoAdminProfilPasswordUpdateWithoutCredentialsRedirectWith302(): void
    {

        $client = static::createClient();

        $client->request('GET', '/compo-admin/account/profil/password-update');

        self::assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function testGoAdminProfilPasswordUpdateWithoutCredentialsRedirectToAdminLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/compo-admin/account/profil/password-update');

        self::assertResponseRedirects('http://localhost/compo-admin/account/admin-connexion');
    }

}
