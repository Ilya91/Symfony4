<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Doctrine\ORM\Mapping\Table;

class PostFixtures extends Fixture implements DependentFixtureInterface
{

    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $user = $this->em->getRepository(User::class)->find(1);
        for ($i = 0; $i < 30; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence($nbWords = 3));
            $post->setContent($faker->text);
            $post->setUser($user);
            $post->setCreatedAt($faker->dateTime($max = 'now', $timezone = null));
            $post->setUpdatedAt($faker->dateTime($max = 'now', $timezone = null));
            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
