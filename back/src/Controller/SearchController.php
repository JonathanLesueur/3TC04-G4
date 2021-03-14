<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\BlogPost;
use App\Entity\User;
use App\Entity\Offer;
use App\Entity\RapidPost;
use App\Entity\BlogPostChannel;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search", methods={"GET","POST"})
     */
    public function index(): Response
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    /**
     * @Route("/ajaxsearch", name="search", methods={"POST"})
     */
    public function AjaxSearch(Request $request): Response
    {
        $text = $request->request->get('text');

        $blogRepository = $this->getDoctrine()->getRepository(BlogPost::class);
        $_blogPosts = $blogRepository->findAll();

        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $_users = $userRepository->findAll();
        
        $_array = [];

        foreach($_blogPosts as $post) {
            $_element = $this->BlogPostToArray($post);
            $_array[] = $_element;
        }

        foreach($_users as $user) {
            $_element = $this->UsertoArray($user);
            $_array[] = $_element;
        }

        $jsonFinal = json_encode($_array);

        return new Response($jsonFinal, Response::HTTP_OK, ['content-type' => 'application/json']);
    }

    private function BlogPostToArray(BlogPost $post): Array
    {
        $_element = [
            'id' => $post->getId(),
            'type' => 'blogpost',
            'title' => $post->getTitle()
        ];

        return $_element;
    }

    private function UsertoArray(User $user): Array
    {
        $_element = [
            'id' => $user->getId(),
            'type' => 'user',
            'title' => $user->getFirstName().' '.$user->getLastName()
        ];

        return $_element;
    }
}
