<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    private $slugify;

    public function __construct(slugify $slugify)
    {
        $this->slugify=$slugify;
    }

    public function load(ObjectManager $manager)
    {
        for($i=0; $i < 200; $i++){
            $episode = new Episode();
            $faker = Faker\Factory::create('en_US');
            $episode->setNumber($faker->numberBetween(1,30));
            $episode->setTitle($faker->text($maxNbChars = rand(20,50)) );
            $episode->setSynopsis($faker->text);
            $episode->setSeason($this->getReference('season_' . rand(0,49)));
            $slug = $this->slugify->generate($episode->getTitle());
            $episode->setSlug($slug);
            $manager->persist($episode);

        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
