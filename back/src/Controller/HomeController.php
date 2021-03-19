<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\BlogPostRepository;
use App\Repository\OfferRepository;
use App\Repository\RapidPostChannelRepository;
use App\Repository\AssociationRepository;

class HomeController extends AbstractController
{   
    protected $blogPostRepository;
    protected $offerRepository;
    protected $channelRepository;
    protected $associationRepository;

    public function __construct(BlogPostRepository $blogPostRepository, OfferRepository $offerRepository, RapidPostChannelRepository $channelRepository, AssociationRepository $associationRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
        $this->offerRepository = $offerRepository;
        $this->channelRepository = $channelRepository;
        $this->associationRepository = $associationRepository;
    }
   
    /**
     * @Route("/home", name="home")
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        $_assocPosts = $this->blogPostRepository->findWithAssociation(4,0);
        $_blogPosts = $this->blogPostRepository->findWithoutAssociation(5,0);
        $_offers = $this->offerRepository->findBy(array(), array('id' => 'DESC'), 5, 0);
        $_channels = $this->channelRepository->findBy(array(), array('id' => 'DESC'), 6, 0);

        return $this->render('home/index.html.twig', [
            'blogPosts' => $_blogPosts,
            'offers' => $_offers,
            'channels' => $_channels,
            'associationsPosts' => $_assocPosts
        ]);
    }

    /**
     * @Route("/help", name="help")
     * @IsGranted("ROLE_USER")
     */
    public function help(): Response
    {
        return $this->render('home/help.html.twig');
    }
}
