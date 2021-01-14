<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Entity\Magasin;
use App\Entity\Produit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController {

    /**
     * @Route("/tempo_remove", name="tempo_remove")
     * @param SessionInterface $session
     */
    public function tempo(SessionInterface $session) {
        $session->clear();

        return $this->redirectToRoute('magasin_list');
    }

    /**
     * @Route("/panier", name="commande")
     * @param SessionInterface $session
     * @return Response
     */
    public function index(SessionInterface $session): Response {
        $em = $this->getDoctrine()->getManager();
        $panier = $session->get('panier', []);
        $magasins = [];

        foreach ($panier as $idMagasin => $produits) {
            $products = [];
            foreach ($produits as $idProduit => $quantite) {
                $products[] = [
                    'produit' => $em->getRepository(Produit::class)->find($idProduit),
                    'quantity' => $quantite
                ];
            }

            $magasins[] = [
                'magasin' => $em->getRepository(Magasin::class)->find($idMagasin),
                'produits' => $products
            ];
        }

        return $this->render('commande/index.html.twig', [
            'magasins' => $magasins
        ]);
    }

    /**
     * @Route("/commande/confirmation", name="commande_create")
     * @param SessionInterface $session
     * @return Response
     */
    public function createCommand(SessionInterface $session) {
        $em = $this->getDoctrine()->getManager();
        $panier = $session->get('panier', []);

        /** @var Client $user */
        $user = $this->getUser();

//        foreach ($panier as $idMagasin => $produits) {
//            $commande = new Commande();
//            $prixTot = 0;
//
//            $commande->setClient($user);
//            $commande->setMagasin($em->getRepository(Magasin::class)->find($idMagasin));
//
//            foreach ($produits as $idProduit => $quantity) {
//                $ligneCommande = new LigneCommande();
//                $produit = $em->getRepository(Produit::class)->find($idProduit);
//
//                $ligneCommande->setProduit($produit);
//                $ligneCommande->setCommande($commande);
//                $ligneCommande->setQuantity($quantity);
//                $ligneCommande->setPrixTot($produit->getPrix() * $quantity);
//
//                $prixTot += $ligneCommande->getPrixTot();
//
//                $em->persist($ligneCommande);
//            }
//            $commande->setPrixTotal($prixTot);
//
//            $em->persist($commande);
//        }
//
//        $em->flush();
//        $session->remove('panier');

        /** @var Commande[] $commandes */
        $commandes = $em->getRepository(Commande::class)->findBy(['client' => $user, 'etat' => null]);

        return $this->render('commande/detail.html.twig', [
            'commandes' => $commandes,
            'client' => $user
        ]);
    }

    /**
     * @Route("/commande/add_product/{id}/{id_product}", name="commande_add_product")
     * @Entity("produit", expr="repository.find(id_product)")
     * @param Magasin $magasin
     * @param Produit $produit
     * @param SessionInterface $session
     * @param Request $request
     * @return RedirectResponse
     */
    public function addProduct(Magasin $magasin, Produit $produit, SessionInterface $session, Request $request) {
        if ($this->getUser()) {
            $panier = $session->get('panier', []);

            if (!empty($panier[$magasin->getId()][$produit->getId()])) {
                $panier[$magasin->getId()][$produit->getId()]++;
            } else { $panier[$magasin->getId()][$produit->getId()] = 1; }

            $session->set('panier', $panier);

            return $this->redirectToRoute('commande');
        } else {
            $prevUrl = $request->headers->get('referer');
            $prevPathInfo = Request::create($prevUrl)->getPathInfo();

            $this->addFlash('notif', 'Vous devez être connecté pour pouvoir ajouter des articles dans votre panier.');
            return $this->redirect($prevPathInfo);
        }
    }
}
