<?php

namespace App\Controller;

use App\Entity\RapidPost;
use App\Form\RapidPostType;
use App\Repository\RapidPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/post")
 * @IsGranted("ROLE_USER")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="posts_index", methods={"GET"})
     */
    public function index(RapidPostRepository $rapidPostRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'rapid_posts' => $rapidPostRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rapidPost = new RapidPost();
        $form = $this->createForm(RapidPostType::class, $rapidPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rapidPost);
            $entityManager->flush();

            return $this->redirectToRoute('posts_index');
        }

        return $this->render('post/new.html.twig', [
            'rapid_post' => $rapidPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"})
     */
    public function show(RapidPost $rapidPost): Response
    {
        return $this->render('post/show.html.twig', [
            'rapid_post' => $rapidPost,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="post_edit", methods={"GET","POST"})
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
     * @Route("/delete/{id}", name="post_delete", methods={"DELETE"})
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
}
