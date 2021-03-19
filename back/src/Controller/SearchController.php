<?php

namespace App\Controller;

use App\Entity\Association;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\BlogPost;
use App\Entity\User;
use App\Entity\Offer;
use App\Entity\RapidPost;
use App\Entity\RapidPostChannel;
use App\Form\SearchType;
use PhpParser\Node\Stmt\Break_;
use App\Repository\BlogPostRepository;
use App\Repository\OfferRepository;
use App\Repository\RapidPostChannelRepository;
use App\Repository\AssociationRepository;
use App\Repository\RapidPostRepository;
use App\Repository\UserRepository;

class SearchController extends AbstractController
{
    protected $blogPostRepository;
    protected $offerRepository;
    protected $channelRepository;
    protected $associationRepository;
    protected $rapidPostRepoitory;
    protected $userRepository;

    public function __construct(BlogPostRepository $blogPostRepository, OfferRepository $offerRepository, RapidPostChannelRepository $channelRepository, AssociationRepository $associationRepository, RapidPostRepository $rapidPostRepoitory, UserRepository $userRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
        $this->offerRepository = $offerRepository;
        $this->channelRepository = $channelRepository;
        $this->associationRepository = $associationRepository;
        $this->rapidPostRepoitory = $rapidPostRepoitory;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/search", name="search_index", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        $search_result = false;
        $search_type = false;
        if ($form->isSubmitted() && $form->isValid()) {
            $searchValue = strtolower($form->get('searchtext')->getData());
            $search_type = $form->get('contenttype')->getData();
            
            switch($search_type) {
                case 'user' :
                    $search_result = $this->userRepository->searchWithName($searchValue);
                break;
                case 'channel':
                    $search_result = $this->channelRepository->searchWithName($searchValue);
                break;
                case 'blogpost':
                    $search_result = $this->blogPostRepository->searchWithName($searchValue);
                break;
                case 'rapidpost':
                    $search_result = $this->rapidPostRepository->searchWithName($searchValue);
                break;
                case 'offer':
                    $search_result = $this->offerRepository->searchWithName($searchValue);
                break;
                case 'association':
                    $search_result = $this->associationRepository->searchWithName($searchValue);
                break;
            }
        }

        return $this->render('search/index.html.twig', [
            'form' => $form->createView(),
            'search_result' => $search_result,
            'search_type' => $search_type
        ]);
    }

    /**
     * @Route("/ajaxsearch", name="search", methods={"POST"})
     */
    public function AjaxSearch(Request $request): Response
    {
        $text = strtolower($request->request->get('text'));

        $_blogPosts = $this->blogRepository->searchWithName($text);
        $_users = $this->userRepository->searchWithName($text);
        $_channels = $this->channelRepository->searchWithName($text);
        
        $_array = [];

        foreach($_users as $user) {
            $_element = $this->UsertoArray($user);
            $_array[] = $_element;
        }
        foreach($_blogPosts as $post) {
            $_element = $this->BlogPostToArray($post);
            $_array[] = $_element;
        }

        foreach($_channels as $channel) {
            $_element = $this->ChannelToArray($channel);
            $_array[] = $_element;
        }

        $jsonFinal = json_encode($_array);

        return new Response($jsonFinal, Response::HTTP_OK, ['content-type' => 'application/json']);
    }

    private function BlogPostToArray(BlogPost $post): Array
    {
        $_element = [
            'type' => 'Article',
            'title' => $post->getTitle(),
            'link' => $this->generateUrl('post_show', array('id' => $post->getId()))
        ];

        if($post->getPicture() != '') {
            $_element['picture'] = '/uploads/blog/'.$post->getPicture();
        }
        return $_element;
    }

    private function UsertoArray(User $user): Array
    {
        $_element = [
            'type' => 'Utilisateur',
            'title' => $user->getFirstName().' '.$user->getLastName(),
            'link' => $this->generateUrl('user_profile', array('id' => $user->getId()))
        ];

        if($user->getAvatar() != '') {
            $_element['picture'] = '/uploads/avatars/'.$user->getAvatar();
        }

        return $_element;
    }

    private function ChannelToArray(RapidPostChannel $channel): Array
    {
        $_element = [
            'type' => 'Thématique',
            'title' => $channel->getName(),
            'link' => $this->generateUrl('channel_show', array('id' => $channel->getId()))
        ];
        if($channel->getLogo() != '') {
            $_element['picture'] = '/uploads/channels/'.$channel->getLogo();
        }

        return $_element;
    }
}
