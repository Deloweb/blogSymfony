<?php

namespace App\DataFixtures;

use \DateTime;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 20; $i++) {
            $tag = new Tag();
            $tag->setName($faker->words($nb = 3, $asText = true)  );
            $manager->persist($tag);
        }

        for ($i = 1; $i <= 20; $i++) {
            $tag = new Category();
            $tag->setName($faker->word  );
            $manager->persist($tag);
        }
        $manager->flush();

        for ($i = 1; $i <= 20; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true));
            $article->setDescription($faker->text($maxNbChars = 1000) );
            $article->setDatePublishing($faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null));
            $article->addTag($tag);
            $manager->persist($article);
        }
        
        $manager->flush();
    }
}
