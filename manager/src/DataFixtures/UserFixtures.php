<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                '1111'
            ));
            $user->setEmail($faker->freeEmail);
            $user->setUsername($faker->userName);
            $user->setRoles(['ROLE_ADMIN']);
            $manager->persist($user);
        }

        $manager->flush();

    }
}
