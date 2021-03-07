<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\User;
use App\Form\BlogPostType;
use App\Repository\BlogPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
/**
 * @Route("/blog")
 * @IsGranted("ROLE_USER")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog_index", methods={"GET"})
     */
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'blog_posts' => $blogPostRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="blog_posts_user", methods={"GET"})
     */
    public function user(BlogPostRepository $blogPostRepository, User $user): Response
    {
        
        return $this->render('blog/user_index.html.twig', [
            'blog_posts' => $user->getBlogPosts(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/manage", name="blog_user_management", methods={"GET"})
     */
    public function manage(BlogPostRepository $blogPostRepository): Response
    {
        $user = $this->getUser();
        
        return $this->render('blog/user_index.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/new", name="blog_post_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $blogPost = new BlogPost();
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $pictureFile = $form->get('picture')->getData();
            $blogPost->setAuthor($this->getUser());
            if($pictureFile) {
                $originalFileName = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$pictureFile->guessExtension();


                try {
                    $pictureFile->move(
                        $this->getParameter('upload_blog_directory'),
                        $newFileName
                    );
                } catch(FileException $e) {

                }

                $blogPost->setPicture($newFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blogPost);
            $entityManager->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('blog/new.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_post_show", methods={"GET"})
     */
    public function show(BlogPost $blogPost): Response
    {
        return $this->render('blog/show.html.twig', [
            'blog_post' => $blogPost,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="blog_post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BlogPost $blogPost): Response
    {
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('blog/edit.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BlogPost $blogPost): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogPost->getId(), $request->request->get('_token')) && $this->getUser() == $blogPost->getAuthor()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blogPost);
            $entityManager->flush();
            return $this->redirectToRoute('blog_user_management');
        }

        return $this->redirectToRoute('blog_index');
    }
}
