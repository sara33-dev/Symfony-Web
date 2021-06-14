<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Comment;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\EpisodeType;
use App\Form\CommentType;
use App\Repository\EpisodeRepository;
use App\Repository\SeasonRepository;
use App\Repository\ProgramRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Slugify;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/episode", name="episode_")
 */
class EpisodeController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(EpisodeRepository $episodeRepository): Response
    {
        return $this->render('episode/index.html.twig', [
            'episodes' => $episodeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($episode->getTitle());
            $episode->setSlug($slug);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($episode);
            $entityManager->flush();

            $email = new Email();
            $email
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Un nouvelle épisode a été ajouté !')
                ->html($this->renderView('email/newEpisodeEmail.html.twig', [
                    'episode' => $episode,
                ]));
            $mailer->send($email);

            $this->addFlash("success", "L'épisode a bien été ajouté.");

            return $this->redirectToRoute('episode_index');
        }

        return $this->render('episode/new.html.twig', [
            'episode' => $episode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{episodeSlug}", name="show", methods={"GET"})
     * @ParamConverter("episode", class=Episode::class, options={"mapping": {"episodeSlug": "slug"}})
     */
    public function show(Episode $episode, Request $request, EntityManagerInterface $entityManager, CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findAll();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setEpisode($episode);
            $comment->setAuthor($this->getUser());
            $entityManager->persist($comment);

            $entityManager->flush();

            return $this->redirectToRoute('episode_show', [
                'episodeSlug' =>$episode->getSlug()
            ]);
        }

        return $this->render('episode/show.html.twig', [
            'episode' => $episode,
            'form'    => $form->createView(),
            'comments'=> $comments
        ]);
    }

    /**
     * @Route("/{episodeSlug}/edit", name="edit", methods={"GET","POST"})
     * @ParamConverter("episode", class=Episode::class, options={"mapping": {"episodeSlug": "slug"}})
     */
    public function edit(Request $request, Episode $episode, Slugify $slugify): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($episode->getTitle());
            $episode->setSlug($slug);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("success", "L'épisode a bien été mise à jour.");

            return $this->redirectToRoute('episode_index');
        }

        return $this->render('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{episodeSlug}", name="delete", methods={"POST"})
     * @ParamConverter("episode", class=Episode::class, options={"mapping": {"episodeSlug": "slug"}}))
     */
    public function delete(Request $request, Episode $episode): Response
    {
        if ($this->isCsrfTokenValid('delete'.$episode->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($episode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('episode_index');
    }

    /**
     * @Route("/{episodeSlug}", name="delete_comment", methods={"POST"})
     * @ParamConverter("episode", class=Episode::class, options={"mapping": {"episodeSlug": "slug"}}))
     */
    public function deleteComment(Request $request, Episode $episode, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);

            $entityManager->flush();

            $this->addFlash("danger", "L'épisode a bien été supprimé.");
        }
        return $this->redirectToRoute('episode_show', [
            'episodeSlug' => $episode->getSlug()
        ]);
    }
}
