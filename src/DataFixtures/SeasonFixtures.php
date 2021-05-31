<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    const DESCRIPTION = 'Cette saison est folle, surtout le dernier épisode où le personnage principal meurt.';

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 3; $i++) {
            $season = new Season();
            $season->setProgramId($this->getReference('program_0'));
            $season->setNumber($i + 1);
            $season->setYear(1788 + $i);
            $season->setDescription(self::DESCRIPTION);

            $manager->persist($season);
            $this->addReference('season_' . $i, $season);

        }

        for ($i = 3; $i < 6; $i++) {
            $season = new Season();
            $season->setProgramId($this->getReference('program_1'));
            $season->setNumber($i + 1);
            $season->setYear(1788 + $i);
            $season->setDescription(self::DESCRIPTION);

            $manager->persist($season);
            $this->addReference('season_' . $i, $season);

        }

        for ($i = 6; $i < 9; $i++) {
            $season = new Season();
            $season->setProgramId($this->getReference('program_2'));
            $season->setNumber($i + 1);
            $season->setYear(1788 + $i);
            $season->setDescription(self::DESCRIPTION);

            $manager->persist($season);
            $this->addReference('season_' . $i, $season);

        }

        for ($i = 9; $i < 12; $i++) {
            $season = new Season();
            $season->setProgramId($this->getReference('program_3'));
            $season->setNumber($i + 1);
            $season->setYear(1788 + $i);
            $season->setDescription(self::DESCRIPTION);

            $manager->persist($season);
            $this->addReference('season_' . $i, $season);

        }

        for ($i = 12; $i < 15; $i++) {
            $season = new Season();
            $season->setProgramId($this->getReference('program_4'));
            $season->setNumber($i + 1);
            $season->setYear(1788 + $i);
            $season->setDescription(self::DESCRIPTION);

            $manager->persist($season);
            $this->addReference('season_' . $i, $season);

        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
