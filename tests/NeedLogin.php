<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait NeedLogin
{
    /**
     * @param KernelBrowser $client
     * @param User $user
     */
    public function login(KernelBrowser $client, User $user): void
    {
        //$session = $client->getContainer()->get('session');
        $session = self::$container->get('session');
        //problem with $user->getRoles()
        $token = new UsernamePasswordToken($user, null, 'admin', $user->getRoles());

        $session->set('security_admin', serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());

        $client->getCookieJar()->set($cookie);
    }
}
