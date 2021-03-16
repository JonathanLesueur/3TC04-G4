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
use Knp\Component\Pager\PaginatorInterface;
/**
 * @IsGranted("ROLE_USER")
 */
class MarketController extends AbstractController
{
    /**
     * @Route("/market/{page}", defaults={"page"=1}, name="market_index", methods={"GET"})
     */
    public function index(OfferRepository $offerRepository, PaginatorInterface $paginator, int $page): Response
    {
        $_offers = $this->getDoctrine()->getRepository(Offer::class)->findBy(array(), array('id' => 'DESC'));
        $_pageOffers = $paginator->paginate($_offers, $page, 10);

        return $this->render('market/index.html.twig', [
            'offers' => $_pageOffers,
        ]);
    }

    /**
     * @Route("/market/user/{id}", name="market_offers_user", methods={"GET"})
     */
    public function user(OfferRepository $offerRepository, int $id): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('id' => $id));
        if(!$user) {
            return $this->redirectToRoute('market_index');
        }

        return $this->render('market/user_index.html.twig', [
            'blog_posts' => $user->getBlogPosts(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/market/offer/new", name="market_new_offer", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $offer->setAuthor($this->getUser());

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
     * @Route("/market/offer/{id}", name="market_show_offer", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $offer = $this->getDoctrine()->getRepository(Offer::class)->findOneBy(array('id' => $id));
        if(!$offer) {
            return $this->redirectToRoute('market_index');
        }

        return $this->render('market/show.html.twig', [
            'offer' => $offer,
        ]);
    }

    /**
     * @Route("/market/manage/{page}", defaults={"page"=1}, name="market_user_management", methods={"GET"})
     */
    public function manage(OfferRepository $offerRepository, PaginatorInterface $paginator, int $page): Response
    {
        $user = $this->getUser();
        $_offers = $user->getOffers();
        $_pageOffers = $paginator->paginate($_offers, $page, 20);

        return $this->render('blog/user_manage.html.twig', [
            'user' => $user,
            'offers' => $_pageOffers
        ]);
    }

    /**
     * @Route("/market/offer/edit/{id}", name="market_edit_offer", methods={"GET","POST"})
     */
    public function edit(Request $request, int $id): Response
    {
        $offer = $this->getDoctrine()->getRepository(Offer::class)->findOneBy(array('id' => $id));
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
    public function delete(Request $request, int $id): Response
    {
        $offer = $this->getDoctrine()->getRepository(Offer::class)->findOneBy(array('id' => $id));
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
