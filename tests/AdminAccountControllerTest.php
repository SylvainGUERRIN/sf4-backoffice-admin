<?php


namespace App\Tests;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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

    //tests page connexion
    public function testGoToAdminLoginPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/compo-admin/account/admin-connexion');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorTextContains('h1.mb-5.mt-5.font-weight-normal.text-center',"Veuillez vous connecter");
    }

    public function testLoginWithBadCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/compo-admin/account/admin-connexion');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'hello@world.fr',
            '_password' => 'fakepassword'
        ]);
        $client->submit($form);
        self::assertResponseRedirects('http://localhost/compo-admin/account/admin-connexion');
        $client->followRedirect();
        self::assertSelectorExists('.alert.alert-danger');
    }

    public function testLoginWithGoodCredentials(): void
    {
        $client = static::createClient();
        //with crawler
        $crawler = $client->request('GET', '/compo-admin/account/admin-connexion');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'admin0',
            '_password' => 'password'
        ]);
        $client->submit($form);
        self::assertResponseRedirects('http://localhost/compo-admin/administration/');
        //can add conditions when redirect
        $client->followRedirect();
        self::assertSelectorExists('h1','Bienvenue sur votre administration');
    }

    public function testPostLoginWithGoodCredentials(): void
    {
        $client = static::createClient();
        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('authenticate');
        $client->request('POST', '/compo-admin/account/admin-connexion', [
            '_username' => 'admin0',
            '_password' => 'password',
            '_csrf_token' => $csrfToken,
        ]);
        self::assertResponseRedirects('http://localhost/compo-admin/administration/');
    }

    /*public function testLetAuthenticatedUserAccessAuth(): void
    {
        $client = static::createClient();
        $user = $client->getContainer()->get('doctrine')->getRepository('App:User')->findOneByUsername('admin0');

        $session = $client->getContainer()->get('session');
        //problem with $user->getRoles()
        $token = new UsernamePasswordToken($user, null, 'admin', $user->getRoles());

        $session->set('security_admin', serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());

        $client->getCookieJar()->set($cookie);
        $client->request('GET', '/compo-admin/account/admin-connexion');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }*/

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
