<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    const DESCRIPTION = 'Cet épisode est incroyable, surtout à la fin où les deux personnages principaux s\'avouent leur amour';

    public function load(ObjectManager $manager)
    {
        for ($i=0; $i<3; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_0'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }

        for ($i=3; $i<6; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_1'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }

        for ($i=6; $i<9; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_2'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }

        for ($i=9; $i<12; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_3'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }

        for ($i=12; $i<15; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_4'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }

        for ($i=15; $i<18; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_5'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }

        for ($i=18; $i<21; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_6'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }

        for ($i=21; $i<24; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_7'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }

        for ($i=24; $i<27; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_8'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }

        for ($i=27; $i<30; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_9'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }

        for ($i=30; $i<33; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_10'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }

        for ($i=33; $i<36; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_11'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }

        for ($i=36; $i<39; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_12'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }

        for ($i=39; $i<42; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_13'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
        }

        for ($i=42; $i<45; $i++) {
            $episode = new Episode();
            $episode->setSeasonId($this->getReference('season_14'));
            $episode->setTitle('episode ' . ($i + 1));
            $episode->setNumber($i + 1);
            $episode->setDescription(self::DESCRIPTION);
            $manager->persist($episode);
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
