<?php

namespace App\Controller;

use App\Entity\Association;
use App\Entity\BlogPost;
use App\Form\BlogPostType;
use App\Form\AssociationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\BlogPostRepository;
use App\Repository\AssociationRepository;


/**
 * @IsGranted("ROLE_USER")
 */
class AssociationController extends AbstractController
{
    protected $blogPostRepository;
    protected $associationRepository;

    public function __construct(BlogPostRepository $blogPostRepository, AssociationRepository $associationRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
        $this->associationRepository = $associationRepository;
    }

    /**
     * @Route("/associations/{page}", defaults={"page"=1}, name="associations_index", methods={"GET"})
     */
    public function index(PaginatorInterface $paginator, int $page): Response
    {
        
        $_blogPosts = $this->blogPostRepository->findWithAssociation();
        $_pageBlogPosts = $paginator->paginate($_blogPosts, $page, 10);

        return $this->render('association/index.html.twig', [
            'associations' => $this->associationRepository->findAll(),
            'blogPosts' => $_pageBlogPosts,
        ]);
    }

    /**
     * @Route("/association/manage/{id}/{page}", defaults={"page"=1}, name="association_manage", methods={"GET"})
     */
    public function manage(PaginatorInterface $paginator, int $page, Association $association): Response
    {
        if(!$association) {
            return $this->redirectToRoute('associations_index');
        }
        $isAdmin = $association->searchAdmin($this->getUser());
        if(!$isAdmin) {
            return $this->redirectToRoute('associations_index');
        }

        $_blogPosts = $association->getBlogPosts();
        $_pageBlogPosts = $paginator->paginate($_blogPosts, $page, 20);

        return $this->render('association/association_manage.html.twig', [
            'association' => $association,
            'blogPosts' => $_pageBlogPosts
        ]);
    }


    /**
     * @Route("/association/new", name="association_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $association = new Association();
        $form = $this->createForm(AssociationType::class, $association);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $logoFile = $form->get('logo')->getData();
            if($logoFile) {
                $originalFileName = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$logoFile->guessExtension();

                try {
                    $logoFile->move(
                        $this->getParameter('upload_associations_directory'),
                        $newFileName
                    );
                } catch(FileException $e) {

                }
                $association->setLogo($newFileName);
            }

            $coverPicture = $form->get('coverpicture')->getData();
            if($coverPicture) {
                $originalFileName = pathinfo($coverPicture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$coverPicture->guessExtension();

                try {
                    $coverPicture->move(
                        $this->getParameter('upload_associations_directory'),
                        $newFileName
                    );
                } catch(FileException $e) {

                }
                $association->setCoverpicture($newFileName);
            }
            $association->addAdmin($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($association);
            $entityManager->flush();

            return $this->redirectToRoute('associations_index');
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

        $isAdmin = $association->searchAdmin($this->getUser());

        return $this->render('association/show.html.twig', [
            'association' => $association,
            'blogPosts' => $_pageBlogPosts,
            'userIsAdmin' => $isAdmin
        ]);
    }

    /**
     * @Route("/association/post/new/{id}", name="association_new_post", methods={"GET","POST"})
     */
    public function post_new(Request $request, SluggerInterface $slugger, int $id): Response
    {
        $association = $this->associationRepository->findOneBy(array('id' => $id));
        if(!$association) {
            return $this->redirectToRoute('associations_index');
        }
        $isAdmin = $association->searchAdmin($this->getUser());
        if(!$isAdmin) {
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
