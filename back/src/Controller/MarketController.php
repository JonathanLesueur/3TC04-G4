<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\OfferComment;
use App\Entity\User;
use App\Form\OfferType;
use App\Form\OfferCommentType;
use App\Form\SearchOfferType;
use App\Repository\OfferRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @IsGranted("ROLE_USER")
 */
class MarketController extends AbstractController
{
    /**
     * @Route("/market/{page}", defaults={"page"=1}, name="market_index", methods={"GET", "POST"})
     */
    public function index(OfferRepository $offerRepository, PaginatorInterface $paginator, int $page, Request $request): Response
    {
        $form = $this->createForm(SearchOfferType::class);
        $form->handleRequest($request);
        
        $hasPage = true;
        $_pageOffers = 'no';
        if ($form->isSubmitted() && $form->isValid()) {

            $name = strtolower(trim($form->get('title')->getData()));
            $type = $form->get('type')->getData();
            $price = $form->get('price')->getData();

            $_pageOffers = $offerRepository->searchCustom($name, $type, $price);
            $hasPage = false;
        } else {
            $_offers = $offerRepository->findAll();
            $_pageOffers = $paginator->paginate($_offers, $page, 10);
        }

        return $this->render('market/index.html.twig', [
            'offers' => $_pageOffers,
            'hasPage' => $hasPage,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/market/user/{id}/{page}", defaults={"page"=1}, name="market_offers_user", methods={"GET"})
     */
    public function user(UserRepository $userRepository, int $id, PaginatorInterface $paginator, int $page): Response
    {
        $user = $userRepository->findOneBy(array('id' => $id));
        if(!$user) {
            return $this->redirectToRoute('market_index');
        }

        $_offers = $user->getOffers();
        $_pageOffers = $paginator->paginate($_offers, $page, 10);

        return $this->render('market/user_index.html.twig', [
            'offers' => $_pageOffers,
            'user' => $user
        ]);
    }

    /**
     * @Route("/market/offer/new", name="market_new_offer", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $offer->setAuthor($this->getUser());

            $pictureFile = $form->get('picture')->getData();

            if($pictureFile) {
                $originalFileName = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName.'-'.uniqid().'.'.$pictureFile->guessExtension();


                try {
                    $pictureFile->move(
                        $this->getParameter('upload_offer_directory'),
                        $newFileName
                    );
                } catch(FileException $e) {

                }

                $offer->setPicture($newFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->flush();

            return $this->redirectToRoute('market_index');
        }

        return $this->render('market/new.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/market/offer/{id}", name="market_show_offer", methods={"GET", "POST"})
     */
    public function show(int $id, OfferRepository $offerRepository, Request $request): Response
    {
        
        $offer = $offerRepository->findOneBy(array('id' => $id));
        if(!$offer) {
            return $this->redirectToRoute('market_index');
        }

        $offerComment = new OfferComment();
        $form = $this->createForm(OfferCommentType::class, $offerComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offerComment->setAuthor($this->getUser());
            


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offerComment);

            $offer->addOfferComment($offerComment);
            $entityManager->flush();
        }

       

        return $this->render('market/show.html.twig', [
            'offer' => $offer,
            'user' => $offer->getAuthor(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/offers/manage/{page}", defaults={"page"=1}, name="market_user_management", methods={"GET"})
     */
    public function manage(PaginatorInterface $paginator, int $page): Response
    {
        $user = $this->getUser();
        $_offers = $user->getOffers();
        $_pageOffers = $paginator->paginate($_offers, $page, 20);

        return $this->render('market/user_manage.html.twig', [
            'user' => $user,
            'offers' => $_pageOffers
        ]);
    }

    /**
     * @Route("/market/offer/edit/{id}", name="market_edit_offer", methods={"GET","POST"})
     */
    public function edit(Request $request, int $id, OfferRepository $offerRepository): Response
    {
        $offer = $offerRepository->findOneBy(array('id' => $id));
        if(!$offer) {
            return $this->redirectToRoute('market_index');
        }

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offer->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('market_index');
        }

        return $this->render('market/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/market/offer/delete/{id}", name="market_delete_offer", methods={"DELETE"})
     */
    public function delete(Request $request, int $id, OfferRepository $offerRepository): Response
    {
        $offer = $offerRepository->findOneBy(array('id' => $id));
        if(!$offer) {
            return $this->redirectToRoute('market_index');
        }

        if ($this->isCsrfTokenValid('delete'.$offer->getId(), $request->request->get('_token')) && $this->getUser() == $offer->getAuthor()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offer);
            $entityManager->flush();

            return $this->redirectToRoute('market_user_management');
        }

        return $this->redirectToRoute('market_index');
    }
}
