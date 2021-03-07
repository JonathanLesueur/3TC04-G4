<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\User;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/market")
 * @IsGranted("ROLE_USER")
 */
class MarketController extends AbstractController
{
    /**
     * @Route("/", name="market_index", methods={"GET"})
     */
    public function index(OfferRepository $offerRepository): Response
    {
        return $this->render('market/index.html.twig', [
            'offers' => $offerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="market_offers_user", methods={"GET"})
     */
    public function user(OfferRepository $offerRepository, User $user): Response
    {
        
        return $this->render('market/user_index.html.twig', [
            'blog_posts' => $user->getBlogPosts(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/new", name="market_new_offer", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('offer_index');
        }

        return $this->render('market/new.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="market_show_offer", methods={"GET"})
     */
    public function show(Offer $offer): Response
    {
        return $this->render('market/show.html.twig', [
            'offer' => $offer,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="market_edit_offer", methods={"GET","POST"})
     */
    public function edit(Request $request, Offer $offer): Response
    {
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('offer_index');
        }

        return $this->render('market/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="market_delete_offer", methods={"DELETE"})
     */
    public function delete(Request $request, Offer $offer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offer->getId(), $request->request->get('_token')) && $this->getUser() == $offer->getAuthor()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offer);
            $entityManager->flush();

            return $this->redirectToRoute('market_user_management');
        }

        return $this->redirectToRoute('offer_index');
    }
}
