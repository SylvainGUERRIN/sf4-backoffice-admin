<?php


namespace App\Tests;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testCount(): void
    {
        //$kernel = self::bootKernel();
        //$nbUsers = $kernel->getContainer()->get(UserRepository::class)->count([]);
        $nbUsers = $this->entityManager->getRepository(User::class)->findAll();
        //self::assertEquals(0, $nbUsers);
        self::assertCount(10, $nbUsers);
    }
}
