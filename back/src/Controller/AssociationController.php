<?php

namespace App\Controller;

use App\Entity\Association;
use App\Entity\BlogPost;
use App\Form\BlogPostType;
use App\Form\AssociationType;
use App\Repository\AssociationRepository;
use App\Repository\BlogPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Doctrine\Common\Collections\Criteria;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @IsGranted("ROLE_USER")
 */
class AssociationController extends AbstractController
{
    /**
     * @Route("/associations/{page}", defaults={"page"=1}, name="associations_index", methods={"GET"})
     */
    public function index(AssociationRepository $associationRepository, BlogPostRepository $blogPostRepository, PaginatorInterface $paginator, int $page): Response
    {
        $_blogPosts = $blogPostRepository->findWithAssociation();
        $_pageBlogPosts = $paginator->paginate($_blogPosts, $page, 10);

        return $this->render('association/index.html.twig', [
            'associations' => $associationRepository->findAll(),
            'blogPosts' => $_pageBlogPosts,
        ]);
    }


    /**
     * @Route("/association/new", name="association_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $association = new Association();
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($association);
            $entityManager->flush();

            return $this->redirectToRoute('association_index');
        }

        return $this->render('association/new.html.twig', [
            'association' => $association,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/association/{id}/{page}", defaults={"page"=1}, name="association_show", methods={"GET"})
     */
    public function show(Association $association, PaginatorInterface $paginator, int $page): Response
    {
        $_blogPosts = $association->getBlogPosts();
        $_pageBlogPosts = $paginator->paginate($_blogPosts, $page, 10);

        return $this->render('association/show.html.twig', [
            'association' => $association,
            'blogPosts' => $_pageBlogPosts
        ]);
    }

    /**
     * @Route("/association/edit/{id}", name="association_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Association $association): Response
    {
        //$searchUser = $association->getAdmins();
        //$criteria = Criteria::create()->where(Criteria::expr()->eq('user_id', $this->getUser()->getId()));
        //$searchUser->matching($criteria);

        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('association_index');
        }

        return $this->render('association/edit.html.twig', [
            'association' => $association,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/association/post/new/{id}", name="association_new_post", methods={"GET","POST"})
     */
    public function post_new(Request $request, SluggerInterface $slugger, Association $association): Response
    {
        $userExist = $association->searchAdmin($this->getUser());

        if(!$userExist) {
            return $this->redirectToRoute('associations_index');
        }

        $blogPost = new BlogPost();
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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
                } catch(FileException $e) {

                }

                $blogPost->setPicture($newFileName);
            }
            $blogPost->setAssociation($association);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blogPost);
            $entityManager->flush();

            return $this->redirectToRoute('associations_index');
        }

        return $this->render('blog/new.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form->createView(),
        ]);
    }
}
