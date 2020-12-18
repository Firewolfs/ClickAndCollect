<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $products = [];

        foreach ($panier as $idProduit => $quantity) {
            $products[] = [
                'produit' => $em->getRepository(Produit::class)->find($idProduit),
                'quantity' => $quantity
            ];
        }

        return $this->render('commande/index.html.twig', [
            'produits' => $products
        ]);
    }

    /**
     * @Route("/commande/create", name="commande_create")
     * @param SessionInterface $session
     * @return RedirectResponse
     */
    public function createCommand(SessionInterface $session) {
        $panier = $session->get('panier', []);

        return $this->redirectToRoute('magasin_list');
    }

    /**
     * @Route("/commande/add_product/{id}", name="commande_add_product")
     * @param Produit $produit
     * @param SessionInterface $session
     * @return RedirectResponse
     */
    public function addProduct(Produit $produit, SessionInterface $session) {
        $panier = $session->get('panier', []);

        if (!empty($panier[$produit->getId()])) {
            $panier[$produit->getId()]++;
        } else { $panier[$produit->getId()] = 1; }

        $session->set('panier', $panier);

        return $this->redirectToRoute('magasin_list');
    }
}
