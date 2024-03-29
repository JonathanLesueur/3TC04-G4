<?php

namespace App\Controller;

use App\Entity\RapidPostChannel;
use App\Entity\RapidPost;
use App\Form\RapidPostChannelType;
use App\Form\RapidPostTypeWithoutChannel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\RapidPostChannelRepository;
use App\Repository\RapidPostRepository;

/**
 * @IsGranted("ROLE_USER")
 */
class ChannelController extends AbstractController
{
    protected $channelRepository;
    protected $rapidPostRepository;

    public function __construct(RapidPostChannelRepository $channelRepository, RapidPostRepository $rapidPostRepository)
    {
        $this->channelRepository = $channelRepository;
        $this->rapidPostRepository = $rapidPostRepository;
    }

    /**
     * @Route("/channels/{page}",defaults={"page"=1}, name="channels_index", methods={"GET"})
     */
    public function index(int $page, PaginatorInterface $paginator): Response
    {

        $_channels = $this->channelRepository->findBy(array(), array('name' => 'ASC'));
        $_pageChannels = $paginator->paginate($_channels, $page, 8);
        $_posts = $this->rapidPostRepository->findBy(array('type' => 'initial'), array('id' => 'DESC'), 5, 0);

        return $this->render('channel/index.html.twig', [
            'channels' => $_pageChannels,
            'posts' => $_posts
        ]);
    }

    /**
     * @Route("/channel/new", name="channel_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $rapidPostChannel = new RapidPostChannel();
        $form = $this->createForm(RapidPostChannelType::class, $rapidPostChannel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rapidPostChannel->setType('manual');
            $pictureFile = $form->get('logo')->getData();
            if($pictureFile) {
                $originalFileName = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$pictureFile->guessExtension();


                try {
                    $pictureFile->move(
                        $this->getParameter('upload_channel_directory'),
                        $newFileName
                    );
                } catch(FileException $e) {}

                $rapidPostChannel->setLogo($newFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rapidPostChannel);
            $entityManager->flush();

            return $this->redirectToRoute('channel_show', ['id' => $rapidPostChannel->getId()]);
        }

        return $this->render('channel/new.html.twig', [
            'rapid_post_channel' => $rapidPostChannel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/channel/{id}", name="channel_show", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $channel = $this->channelRepository->findOneBy(array('id' => $id));
        if(!$channel) {
            return $this->redirectToRoute('channels_index');
        }
        return $this->render('channel/show.html.twig', [
            'channel' => $channel
        ]);
    }

    /**
     * @Route("/channel/new/{id}", name="channel_new_post", methods={"GET","POST"})
     */
    public function newPost(Request $request, int $id): Response
    {
        $channel = $this->channelRepository->findOneBy(array('id' => $id));
        if(!$channel) {
            return $this->redirectToRoute('channels_index');
        }
        
        $rapidPost = new RapidPost();
        $form = $this->createForm(RapidPostTypeWithoutChannel::class, $rapidPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();

            $rapidPost->setAuthor($this->getUser());
            $rapidPost->addChannel($channel);

            $entityManager->persist($rapidPost);
            $entityManager->flush();

            return $this->redirectToRoute('post_show', array('id' => $rapidPost->getId()));
        }

        return $this->render('channel/newPost.html.twig', [
            'rapid_post' => $rapidPost,
            'channel' => $channel,
            'form' => $form->createView(),
        ]);
    }
}
