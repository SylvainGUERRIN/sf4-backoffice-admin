<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        // create 20 users!
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername('admin'.$i);
            $user->setMail('admin'.$i.'@orange.fr');

            if($i % 2 === 0){
                $user->setRole('ROLE_ADMIN');
            }else{
                $user->setRole('ROLE_USER');
            }

            $password = $this->encoder->encodePassword($user, 'password');
            $user->setPass($password);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
