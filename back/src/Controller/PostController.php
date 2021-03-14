<?php

namespace App\Controller;

use App\Entity\RapidPost;
use App\Entity\RapidPostChannel;
use App\Form\RapidPostType;
use App\Repository\RapidPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/posts/{page}", defaults={"page"=1}, name="posts_index", methods={"GET"})
     */
    public function index(RapidPostRepository $rapidPostRepository, int $page): Response
    {
        $_posts =  $rapidPostRepository->findBy(array('type' => 'initial'), array(), 10, 0);

        return $this->render('post/index.html.twig', [
            'posts' => $_posts,
        ]);
    }

    /**
     * @Route("/post/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rapidPost = new RapidPost();
        $form = $this->createForm(RapidPostType::class, $rapidPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $rapidPost->setAuthor($this->getUser());

            // Récupération des thématiques inscrites puis on scinde les entrées en un tableau
            $channels = $form['channels']->getData();
            $_channels = explode(',', $channels);

            // On vient parcourir ce tableau pour nettoyer les noms des thématiques et vérifier qu'elle n'existent pas déjà
            foreach($_channels as $channel) {
                $channelName = ucfirst(trim($channel));
                $chanRepository = $this->getDoctrine()->getRepository(RapidPostChannel::class);
                $search = $chanRepository->searchWithName($channelName);
                // Si elle existe, alors on l'associe au post qui vient d'être écrit
                if($search) {
                    $rapidPost->addChannel($search);
                } else { // Sinon, on créé une nouvelle thématique puis on l'associe au post
                    $chan = new RapidPostChannel();
                    $chan->setType('auto');
                    $chan->setName($channelName);
                    $entityManager->persist($chan);
                    $rapidPost->addChannel($chan);
                }
            }

            $entityManager->persist($rapidPost);
            $entityManager->flush();

            return $this->redirectToRoute('post_show', array('id' => $rapidPost->getId()));
        }

        return $this->render('post/new.html.twig', [
            'rapid_post' => $rapidPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_show", methods={"GET"})
     */
    public function show(RapidPost $rapidPost): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $rapidPost
        ]);
    }

    /**
     * @Route("/post/edit/{id}", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RapidPost $rapidPost): Response
    {
        $form = $this->createForm(RapidPostType::class, $rapidPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('posts_index');
        }

        return $this->render('post/edit.html.twig', [
            'rapid_post' => $rapidPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post/delete/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, RapidPost $rapidPost): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rapidPost->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rapidPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('posts_index');
    }

    /**
     * @Route("/post/rep/{id}", name="post_rep", methods={"GET", "POST"})
     */
    public function rep(Request $request, RapidPost $rapidPost): Response
    {

        return $this->redirectToRoute('posts_index');
    }
}
