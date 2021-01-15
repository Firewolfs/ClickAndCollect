<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\CommandeStatut;
use App\Entity\Creneau;
use App\Entity\LigneCommande;
use App\Entity\Magasin;
use App\Entity\Produit;
use App\Form\SelectionCreneauType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController {
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

        foreach ($panier as $idMagasin => $produits) {
            $commande = new Commande();
            $prixTot = 0;

            $commande->setClient($user);
            $commande->setMagasin($em->getRepository(Magasin::class)->find($idMagasin));

            $magasin = $em->getRepository(Magasin::class)->find($idMagasin);
            foreach ($magasin->getCreneaux() as $creneau){
                $creneauForm[$creneau->getDate()->format('H:i:s')] = $creneau->getId() ;
            }
            $selectionCreneauForm = $this->createForm(SelectionCreneauType::class, null, ['creneaux' => $creneauForm]);

            foreach ($produits as $idProduit => $quantity) {
                $ligneCommande = new LigneCommande();
                $produit = $em->getRepository(Produit::class)->find($idProduit);

                $ligneCommande->setProduit($produit);
                $ligneCommande->setCommande($commande);
                $ligneCommande->setQuantity($quantity);
                $ligneCommande->setPrixTot($produit->getPrix() * $quantity);

                $prixTot += $ligneCommande->getPrixTot();

                $em->persist($ligneCommande);
            }
            $commande->setPrixTotal($prixTot);

            $em->persist($commande);
        }

        $em->flush();
        $session->remove('panier');

        /** @var Commande[] $commandes */
        $commandes = $em->getRepository(Commande::class)->findBy(['client' => $user, 'etat' => null]);

        return $this->render('commande/detail.html.twig', [
            'commandes' => $commandes,
            'client' => $user,

        ]);
    }

    /**
     * @Route("/commande", name="commande_list")
     */
    public function mesCommandes(){
        $em = $this->getDoctrine()->getManager();
        $commandesEnCours = $em->getRepository(Commande::class)->findCommandeEnCours($this->getUser()->getId());
        $commandesTerminer = $em->getRepository(Commande::class)->findCommandeTerminer($this->getUser());

        //dd($commandesEnCours);
        //dd($commandesTerminer);

        return $this->render('commande/listCommande.html.twig',[
            'commandesEnCours' => $commandesEnCours,
            'commandesTerminer' => $commandesTerminer,
        ]);
    }

    /**
     * @Route("/commande/creneau/{id}", name="commande_creneau")
     * @param Request $request
     * @param Commande $commande
     * @return Response
     */
    public function commandeCreneau(Request $request, Commande $commande){
        $em = $this->getDoctrine()->getManager();
        $creneaux = $commande->getMagasin()->getCreneauxDisponible();


        foreach ($creneaux as $creneau){
            $creneauForm[$creneau->getDate()->format('d-m-y H:i:s')] = $creneau->getId();
        }
        $selectionCreneauForm = $this->createForm(SelectionCreneauType::class, null, ['creneaux' => $creneauForm]);

        $selectionCreneauForm->handleRequest($request);

        if($selectionCreneauForm->isSubmitted() && $selectionCreneauForm->isValid()){
            $creneauSelected = $em->getRepository(Creneau::class)->find($selectionCreneauForm['creneaux']->getData());
            $commande->setCreneau($creneauSelected);
            $em->persist($commande);
            $em->flush();
        }

        return $this->render('commande/commande-creneau.html.twig',[
            'formCreneau' => $selectionCreneauForm->createView(),
        ]);
    }

    /**
     * @Route("/commande/payer", name="commande_paye")
     * @param Swift_Mailer $mailer
     * @return RedirectResponse
     */
    public function payer(Swift_Mailer $mailer) {
        $em = $this->getDoctrine()->getManager();
        /** @var Client $user */
        $user = $this->getUser();

        /** @var Commande[] $commandes */
        $commandes = $em->getRepository(Commande::class)->findBy(['client' => $user, 'etat' => null]);

        foreach ($commandes as $commande) {
            $commande->setEtat($em->getRepository(CommandeStatut::class)->find(1));
            $em->persist($commande);
        }
        $em->flush();

        $message = new Swift_Message('Confirmation de commande');
        $message->setFrom('dev.lpmetinet@gmail.com')
            ->setTo($user->getEmail())
            ->setBody($this->renderView('commande/confirmation-email.html.twig', [
                'commandes' => $commandes
            ]), 'text/html');

        $mailer->send($message);

        return $this->redirectToRoute('magasin_list');
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

    /**
     * @Route("/commande/remove/{id}/{id_product}", name="commande_remove_product")
     * @Entity("produit", expr="repository.find(id_product)")
     * @param Magasin $magasin
     * @param Produit $produit
     * @param SessionInterface $session
     * @return RedirectResponse
     */
    public function removeProduct(Magasin $magasin, Produit $produit, SessionInterface $session) {
        $panier = $session->get('panier', []);

        unset($panier[$magasin->getId()][$produit->getId()]);
        if (empty($panier[$magasin->getId()])) { unset($panier[$magasin->getId()]); }

        $session->set('panier', $panier);

        return $this->redirectToRoute('commande');
    }
}
