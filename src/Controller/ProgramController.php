<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\ProgramType;
use App\Form\SearchProgramFormType;
use App\Form\SeasonType;
use App\Repository\CommentRepository;
use App\Repository\ProgramRepository;
use App\Service\Slugify;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/programs", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(Request $request, ProgramRepository $programRepository): Response
    {
        $form = $this->createForm(SearchProgramFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $programs = $programRepository->findLikeNameOrActor($search);
        } else {
            $programs = $programRepository->findAll();
        }

        return $this->render('program/index.html.twig', [
                'programs' => $programs,
                'form'     => $form->createView(),
                ]);
    }

    /**
     * The controller for the program add form
     * Display the form or deal with it
     * @Route("/new", name="new")
     */
    public function new(EntityManagerInterface $entityManager, Request $request, Slugify $slugify, MailerInterface $mailer) : Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $program->setOwner($this->getUser());

            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Une nouvelle série a été publiée !')
                ->html($this->renderView('email/newProgramEmail.html.twig', [
                    'program' => $program
                ]));
            $mailer->send($email);

            $this->addFlash("success", "La nouvelle série a bien été ajoutée.");

            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }

    /**
     * Getting a program by id
     *
     * @Route("/show/{programSlug}", name="show")
     * @ParamConverter("program", class=Program::class, options={"mapping": {"programSlug": "slug"}})
     * @return Response
     */
    public function show(Program $program): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with this id found in program\'s table.'
            );
        }

        $seasons = $program->getSeasons();

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    /**
     * Getting a program and a season by id
     *
     * @Route("/{programSlug}/seasons/{seasonId}", name="season_show")
     * @ParamConverter("program", class=Program::class, options={"mapping": {"programSlug": "slug"}})
     * @ParamConverter("season", class=Season::class, options={"mapping": {"seasonId": "id"}})
     * @return Response
     */
    public function showSeason(Program $program, Season $season): Response
    {
        $episodes = $season->getEpisodes();

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }

    /**
     * Getting a program and a season by id
     *
     * @Route("/{programSlug}/seasons/{seasonId}/episodes/{episodeSlug}", name="episode_show")
     * @ParamConverter("program", class=Program::class, options={"mapping": {"programSlug": "slug"}})
     * @ParamConverter("season", class=Season::class, options={"mapping": {"seasonId": "id"}})
     * @ParamConverter ("episode", class=Episode::class, options={"mapping": {"episodeSlug": "slug"}})
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {

        return $this->render('program/episode_show.html.twig',[
            'program' => $program,
            'season'  => $season,
            'episode' => $episode
        ]);
    }


    /**
     * @Route("/{slug}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Program $program): Response
    {
        if (!($this->getUser() == $program->getOwner())) {
            throw new AccessDeniedException('only the owner can edit this program');
        }
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("success", "La série a bien été mise à jour.");

            return $this->redirectToRoute('program_index');
        }

        return $this->render('season/edit.html.twig', [
            'season' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/watchlist", name="watchlist", methods={"GET","POST"})
     */
    public function addToWatchList(Program $program, EntityManagerInterface $entityManager): Response
    {

        if ($this->getUser()->getWatchList()->contains($program)) {
            $this->getUser()->removeFromWatchlist($program);
        } else {
            $this->getUser()->addToWatchlist($program);
        }
        $entityManager->flush();
        return $this->redirectToRoute('program_show', [
            'programSlug' => $program->getSlug()
        ]);

    }
}
