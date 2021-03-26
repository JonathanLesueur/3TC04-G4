<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Common\Collections\Criteria;

/**
* @IsGranted("ROLE_USER")
*/
class UserController extends AbstractController
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/profils/{letter}/{page}", defaults={"letter"="all", "page"=1}, name="users_index", methods={"GET"})
     */
    public function index(String $letter, int $page, PaginatorInterface $paginator): Response
    {
        if(strlen($letter) == 1) {
            $_users = $this->userRepository->searchByFirstLetter(strtolower($letter));
        } else {
            $_users = $this->userRepository->findBy(array(), array('lastName' => 'ASC'));
        }
        
        $_pageUsers = $paginator->paginate($_users, $page, 20);
        

        return $this->render('user/index.html.twig', [
            'users' => $_pageUsers,
            'currentLetter' => $letter
        ]);
    }

     /**
     * @Route("/profil/edit", name="user_edit", methods={"GET","POST"})
     */
    
    public function edit(Request $request, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $pictureFile = $form->get('avatar')->getData();
            
            if($pictureFile) {
                $originalFileName = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$pictureFile->guessExtension();
                
                     try {
                        $pictureFile->move(
                            $this->getParameter('upload_avatar_directory'),
                            $newFileName
                        );
                        $user->setAvatar($newFileName);
                    } catch(FileException $e) {

                    }
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profil/{id}", name="user_profile", methods={"GET"})
     */
    public function show(User $user = null): Response
    {
        if(!$user) {
            return $this->redirectToRoute('user_index');
        }
        $criteria = Criteria::create();
        $criteria->orderBy(['createdAt' => 'DESC']);
        $_blogPosts = $user->getBlogPosts()->matching($criteria);

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'blogPosts' => $_blogPosts
        ]);
    }
}
