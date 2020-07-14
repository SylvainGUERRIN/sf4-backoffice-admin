<?php


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MailTest extends WebTestCase
{
    public function testMailSendEmail(): void
    {
        $client = static::createClient();
        $client->enableProfiler();
        $client->request('GET', '/compo-admin/account/inscription');
        //$mailCollector = $client->getProfile()->getCollector('mailer');

        self::assertEmailCount(0);

        //$email = self::getMailerMessage(0);
    }
}
