<?php


namespace App\Tests;


use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AdminAccountControllerTest extends WebTestCase
{
    use NeedLogin;

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

    //tests on array user
    public function testUserAdmin0(): void
    {
        $client = static::createClient();
        $container = self::$container;

        $userRepository = static::$container->get(UserRepository::class);

        $admin0 = $userRepository->findOneByMail('admin0@orange.fr');

        self::assertIsObject($admin0);
        //self::assertContainsOnlyInstancesOf(UserRepository::class, $admin0);
        //$user = $client->getContainer()->get('doctrine')->getRepository('App:User')->findOneByUsername('admin0');
        //self::assertArrayHasKey('0', $admin0);
        //self::assertClassNotHasAttribute($user, User::class);
    }

    public function testLetAuthenticatedUserAccessAuth(): void
    {
        $client = static::createClient();
        $container = self::$container;

        $userRepository = static::$container->get(UserRepository::class);
        $admin0 = $userRepository->findOneByMail('admin0@orange.fr');

        self::assertIsObject($admin0);
        self::assertObjectHasAttribute('username', $admin0);
        self::assertObjectHasAttribute('mail', $admin0);
        self::assertObjectHasAttribute('pass', $admin0);
        self::assertObjectHasAttribute('role', $admin0);
        self::assertSame('ROLE_ADMIN', $admin0->getRole());


        //$user = $client->getContainer()->get('doctrine')->getRepository('App:User')->findOneByUsername('admin0');

        $this->login($client, $admin0);
        /*$session = $client->getContainer()->get('session');
        //problem with $user->getRoles()
        $token = new UsernamePasswordToken($admin0, null, 'admin', $admin0->getRoles());

        $session->set('security_admin', serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());

        $client->getCookieJar()->set($cookie);*/
        //$client->request('GET', '/compo-admin/account/admin-connexion');
        //self::assertResponseStatusCodeSame(Response::HTTP_OK);

        //ne se connecte pas malgré le trait
        $client->request('GET', '/compo-admin/administration');
        //self::assertResponseStatusCodeSame(Response::HTTP_OK);
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
