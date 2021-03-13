<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\BlogPost;
use App\Entity\Offer;
use App\Entity\RapidPostChannel;

class HomeController extends AbstractController
{

   
    /**
     * @Route("/home", name="home")
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        $BlogPostRepository = $this->getDoctrine()->getRepository(BlogPost::class);
        $OfferRepository = $this->getDoctrine()->getRepository(Offer::class);
        $ChannelRepository = $this->getDoctrine()->getRepository(RapidPostChannel::class);

        $_blogPosts = $BlogPostRepository->findBy(array(), array('id' => 'DESC'), 5 , 0);
        $_offers = $OfferRepository->findBy(array(), array('id' => 'DESC'), 5, 0);
        $_channels = $ChannelRepository->findBy(array(), array('id' => 'DESC'), 6, 0);

        return $this->render('home/index.html.twig', [
            'blogPosts' => $_blogPosts,
            'offers' => $_offers,
            'channels' => $_channels
        ]);
    }
}
