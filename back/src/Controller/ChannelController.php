<?php

namespace App\Controller;

use App\Entity\RapidPostChannel;
use App\Form\RapidPostChannelType;
use App\Repository\RapidPostChannelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class ChannelController extends AbstractController
{
    /**
     * @Route("/channels", name="channels_index", methods={"GET"})
     */
    public function index(RapidPostChannelRepository $rapidPostChannelRepository): Response
    {
        return $this->render('channel/index.html.twig', [
            'rapid_post_channels' => $rapidPostChannelRepository->findAll(),
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
                } catch(FileException $e) {

                }

                $rapidPostChannel->setLogo($newFileName);
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rapidPostChannel);
            $entityManager->flush();

            return $this->redirectToRoute('channels_index');
        }

        return $this->render('channel/new.html.twig', [
            'rapid_post_channel' => $rapidPostChannel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/channel/{id}", name="channel_show", methods={"GET"})
     */
    public function show(RapidPostChannel $rapidPostChannel): Response
    {
        return $this->render('channel/show.html.twig', [
            'rapid_post_channel' => $rapidPostChannel,
        ]);
    }

    /**
     * @Route("/channel/edit/{id}", name="channel_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, RapidPostChannel $rapidPostChannel): Response
    {
        $form = $this->createForm(RapidPostChannelType::class, $rapidPostChannel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('channels_index');
        }

        return $this->render('channel/edit.html.twig', [
            'rapid_post_channel' => $rapidPostChannel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/channel/delete/{id}", name="channel_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, RapidPostChannel $rapidPostChannel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rapidPostChannel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rapidPostChannel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('channels_index');
    }
}
