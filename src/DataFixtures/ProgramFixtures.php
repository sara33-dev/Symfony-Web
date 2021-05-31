<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        'Walking Dead',
        'Fear the Walking Dead',
        'Grey\'s Anatomy',
        'House of Cards',
        'Un, Dos, Tres',
    ];

    const SUMMARIES = [
        'Des zombies envahissent la terre',
        'Des zombies envahissent la terre',
        'Cette série met en scène une équipe médicale d\'un hôpital fictif de Seattle : le Seattle Grace Hospital (puis Seattle Grace - Mercy West Hospital, lors de la saison 6 et enfin, Grey Sloan Memorial Hospital, dès la saison 9). Le titre fait référence à Meredith Grey, interne, résidente, titulaire puis chef du service de chirurgie générale, qui a un lien plus ou moins direct avec tous les autres personnages principaux. La série commence lors du premier jour d\'internat de Meredith en chirurgie au Seattle Grace Hospital. Elle va devoir travailler pendant sept ans si elle veut réussir sa spécialité, tout comme ses camarades internes de première année, Cristina Yang, Izzie Stevens, George O\'Malley et Alex Karev. Ils étaient encore étudiants il y a très peu de temps ; aujourd\'hui, ils sont tous médecins, un métier où l\'apprentissage peut mener les patients à la vie comme à la mort, au meilleur comme au pire. Ancienne chirurgienne renommée, la mère de Meredith (la Dre Ellis Grey) est atteinte de la maladie d\'Alzheimer.',
        'Francis Underwood, dit « Frank », coordinateur de la majorité démocrate au Congrès américain et représentant de l\'État de Caroline du Sud, célèbre l\'élection du président Garret Walker, qu\'il a contribué à faire élire de manière déterminante. En contrepartie, Walker s\'engage à nommer Francis secrétaire d\'État dans son cabinet, soit l\'équivalent du ministre des Affaires étrangères. Néanmoins, peu avant l\'investiture du président élu, la chef de cabinet, Linda Vasquez, lui annonce que Walker n\'a pas l\'intention d\'honorer sa promesse. Furieux, Underwood et sa femme Claire, qui comptait sur la nomination de son mari pour développer son ONG de défense de l\'environnement, s\'allient pour détruire ceux qui s\'opposent à leurs projets.',
        'Vingt jeunes entrent dans la meilleure école des arts de la scène, l\'Académie de Carmen Arranz. Ils devront y perfectionner leurs talents pour la danse, la musique et le théâtre, et devenir des professionnels du spectacle. Emmenés par Lola, Silvia, Roberto, Pedro, Ingrid, Jeronimo, Marta et les autres, ils vont former une troupe d\'artistes accomplis et vivre des aventures très intenses.',
    ];

    const POSTERS = [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/9/93/Fear_the_Walking_Dead-Logo.svg/925px-Fear_the_Walking_Dead-Logo.svg.png',
        'https://fr.web.img6.acsta.net/pictures/19/07/19/09/25/2947268.jpg',
        'https://www.ifemdr.fr/wp-content/uploads/2020/01/lemdr-dans-un-episode-de-la-serie-greys-anatomy.png',
        'https://www.cinechronicle.com/wp-content/uploads/2015/03/House-of-Cards-affiche.jpg',
        'https://images.6play.fr/v2/images/3312532/raw?blur=0&fit=scale_crop&format=jpeg&height=630&interlace=1&quality=60&width=1200&hash=313345a338748c642bd1f9d3980d562434ef9ff3',
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAMS as $key => $programName) {
            $program = new Program();
            $program->setTitle($programName);
            $program->setSummary(self::SUMMARIES[$key]);
            $program->setPoster(self::POSTERS[$key]);
            $program->setCategory($this->getReference('category_5'));
            for ($i=0; $i < 5 ; $i++) {
                $program->addActor($this->getReference('actor_' . $i));
            }
            $manager->persist($program);
            $this->addReference('program_' . $key, $program);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ActorFixtures::class,
            CategoryFixtures::class
        ];
    }

}
