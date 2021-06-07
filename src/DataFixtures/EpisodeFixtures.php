<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    const DESCRIPTION = 'Cet épisode est incroyable, surtout à la fin où les deux personnages principaux s\'avouent leur amour';

    private $slug;

    public function __construct(Slugify $slugify)
    {
        $this->slug = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        for ($j=0; $j<14; $j++) {
        for ($i=0; $i<3; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_' . $j));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setSlug($this->slug->generate(($episode->getTitle())));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }
}
