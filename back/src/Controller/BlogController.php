<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\BlogPostComment;
use App\Entity\User;
use App\Entity\Like;
use App\Form\BlogPostType;
use App\Form\BlogPostCommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\BlogPostRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Criteria;

/**
 * @IsGranted("ROLE_USER")
 */
class BlogController extends AbstractController
{
    protected $blogPostRepository;
    protected $userRepository;

    public function __construct(BlogPostRepository $blogPostRepository, UserRepository $userRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
        $this->userRepository = $userRepository;
    }
    
    /**
     * @Route("/blogs/{page}", defaults={"page"=1}, name="blog_index", methods={"GET"})
     */
    public function index(BlogPostRepository $blogPostRepository, PaginatorInterface $paginator, int $page): Response
    {
        $_blogPosts = $blogPostRepository->findWithoutAssociation();
        $_pageBlogPosts = $paginator->paginate($_blogPosts, $page, 10);

        return $this->render('blog/index.html.twig', [
            'blogPosts' => $_pageBlogPosts,
        ]);
    }

    /**
     * @Route("/blog/user/{id}/{page}", defaults={"page"=1}, name="blog_posts_user", methods={"GET"})
     */
    public function user(int $id, int $page, PaginatorInterface $paginator): Response
    {
        $user = $this->userRepository->findOneBy(array('id' => $id));
        if(!$user) {
            return $this->redirectToRoute('blog_index');
        }

        $criteria = Criteria::create();
        $criteria->orderBy(['createdAt' => 'DESC']);
        $_blogPosts = $user->getBlogPosts()->matching($criteria);

        $_pageBlogPosts = $paginator->paginate($_blogPosts, $page, 10);

        return $this->render('blog/user_index.html.twig', [
            'blogPosts' => $_pageBlogPosts,
            'user' => $user
        ]);
    }

    /**
     * @Route("/blog/manage/{page}", defaults={"page"=1}, name="blog_user_management", methods={"GET"})
     */
    public function manage(PaginatorInterface $paginator, int $page): Response
    {
        $user = $this->getUser();
        $_blogPosts = $user->getBlogPosts();
        $_pageBlogPosts = $paginator->paginate($_blogPosts, $page, 20);

        return $this->render('blog/user_manage.html.twig', [
            'user' => $user,
            'blogPosts' => $_pageBlogPosts
        ]);
    }

    /**
     * @Route("/blog/new", name="blog_post_new", methods={"GET","POST"})
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
                } catch(FileException $e) {}

                $blogPost->setPicture($newFileName);
            }
            $blogPost->setAuthor($this->getUser());

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
     * @Route("/blog/{id}", name="blog_post_show", methods={"GET", "POST"})
     */
    public function show(int $id, Request $request): Response
    {   
        $blogPost = $this->blogPostRepository->findOneBy(array('id' => $id));
        if(!$blogPost) {
            return $this->redirectToRoute('blog_index');
        }

        $blogPostComment = new BlogPostComment();
        $form = $this->createForm(BlogPostCommentType::class, $blogPostComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogPostComment->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blogPostComment);
            $blogPost->addBlogPostComment($blogPostComment);
            $entityManager->flush();
        }
        
        if(!$blogPost) {
            return $this->redirectToRoute('blog_index');
        }

        return $this->render('blog/show.html.twig', [
            'blogPost' => $blogPost,
            'user' => $blogPost->getAuthor(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/blog/edit/{id}", name="blog_post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, int $id): Response
    {

        $blogPost = $this->blogPostRepository->findOneBy(array('id' => $id));
        if(!$blogPost) {
            return $this->redirectToRoute('blog_index');
        }

        $author = $blogPost->getAuthor();
        $association = $blogPost->getAssociation();

        if($author) {
            if($this->getUser()->getId() != $author->getId()) {
                return $this->redirectToRoute('blog_index');
            }
        } else if($association) {
            $isAdmin = $association->searchAdmin($this->getUser());
            if(!$isAdmin) {
                return $this->redirectToRoute('associations_index');
            }
        }
        

        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogPost->setUpdatedAt(new \DateTime());
            
            $pictureFile = $form->get('picture')->getData();
            if($pictureFile) {
                $originalFileName = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$pictureFile->guessExtension();

                try {
                    $pictureFile->move(
                        $this->getParameter('upload_blog_directory'),
                        $newFileName
                    );
                } catch(FileException $e) {}

                $blogPost->setPicture($newFileName);
            }

            $this->getDoctrine()->getManager()->flush();
            if($author) {
                return $this->redirectToRoute('blog_user_management');
            } elseif($association) {
                return $this->redirectToRoute('association_manage', array('id' => $association->getId()));
            }
        }

        return $this->render('blog/edit.html.twig', [
            'blogPost' => $blogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blog/delete/{id}", name="blog_post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, int $id): Response
    {
        $blogPost = $this->blogPostRepository->findOneBy(array('id' => $id));
        if(!$blogPost) {
            return $this->redirectToRoute('blog_index');
        }

        $author = $blogPost->getAuthor();
        $association = $blogPost->getAssociation();

        if($author) {
            if($this->getUser()->getId() != $author->getId()) {
                return $this->redirectToRoute('blog_index');
            }
        } else if($association) {
            $isAdmin = $association->searchAdmin($this->getUser());
            if(!$isAdmin) {
                return $this->redirectToRoute('associations_index');
            }
        }


        if ($this->isCsrfTokenValid('delete'.$blogPost->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blogPost);
            $entityManager->flush();

            if($author) {
                return $this->redirectToRoute('blog_user_management');
            } else if($association) {
                return $this->redirectToRoute('association_manage', array('id' => $association->getId()));
            }
        }
    }

    /**
     * @Route("/blog/like/{id}", name="blog_like", methods={"GET"})
     */
    public function rep(BlogPost $blogPost): Response
    {
        if(!$blogPost) {
            return $this->redirectToRoute('blog_index');
        }
        
        $_likes = $this->getUser()->getLikes();
        $hasPreviouslyLike = false;

        foreach($_likes as $like) {
            if ($like->getBlogPost()[0] && $like->getBlogPost()[0]->getId() == $blogPost->getId()) {
                $hasPreviouslyLike = true;
                break;
            }
        }

        if(!$hasPreviouslyLike) {
            $like = new Like();
            $like->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($like);
            $blogPost->addLike($like);
            $entityManager->flush();
        }

        return $this->redirectToRoute('blog_post_show', array('id' => $blogPost->getId()));
    }
}
