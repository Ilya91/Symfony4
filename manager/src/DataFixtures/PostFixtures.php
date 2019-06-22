<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class PostFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i = 0; $i < 30; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence($nbWords = 3));
            $post->setContent($faker->text);
            $post->setUserId($faker->numberBetween(1, 9));
            $post->setCreatedAt($faker->dateTime($max = 'now', $timezone = null));
            $post->setUpdatedAt($faker->dateTime($max = 'now', $timezone = null));
            $manager->persist($post);
        }

        $manager->flush();
    }
}
