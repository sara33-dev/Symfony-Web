<?php


namespace App\Controller;


use App\Entity\Program;
use App\Repository\ProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                array(),
                array('id' => 'DESC'),
                3
            );

        return $this->render('index.html.twig', [
                'programs' => $programs]
        );
    }

    /**
     * @Route("/my-profil", name="app_profil")
     */
    public function profil(): Response
    {
        return $this->render('profil.html.twig');
    }
}
