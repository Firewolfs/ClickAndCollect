<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Commande;
use App\Entity\CommandeStatut;
use App\Entity\Creneau;
use App\Entity\LigneCommande;
use App\Entity\Magasin;
use App\Entity\Produit;
use App\Entity\Stocks;
use App\Form\CommandeType;
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

        $admin = false;
        $user = $this->getUser();
        if($user != null){
            $roles = $user->getRoles();
            foreach ($roles as $role){
                if($role == "ROLE_ADMIN"){
                    $admin = true;
                    break;
                }
            }
        }

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
            'magasins' => $magasins,
            'admin' => $admin
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

        $admin = false;
        if($user != null){
            $roles = $user->getRoles();
            foreach ($roles as $role){
                if($role == "ROLE_ADMIN"){
                    $admin = true;
                    break;
                }
            }
        }

        foreach ($panier as $idMagasin => $produits) {
            $magasin = $em->getRepository(Magasin::class)->find($idMagasin);
            $commande = new Commande();
            $prixTot = 0;

            $commande->setClient($user);
            $commande->setMagasin($magasin);
            $commande->setEtat($em->getRepository(CommandeStatut::class)->find(6));

            foreach ($produits as $idProduit => $quantity) {
                $ligneCommande = new LigneCommande();
                /** @var Produit $produit */
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

        return $this->redirectToRoute('commande_detail');
    }

    /**
     * @Route("/commande/detail", name="commande_detail")
     */
    public function detail() {
        $em = $this->getDoctrine()->getManager();

        /** @var Client $user */
        $user = $this->getUser();

        $admin = false;
        if($user != null){
            $roles = $user->getRoles();
            foreach ($roles as $role){
                if($role == "ROLE_ADMIN"){
                    $admin = true;
                    break;
                }
            }
        }

        /** @var Commande[] $commandes */
        $commandes = $em->getRepository(Commande::class)->findBy(['client' => $user, 'etat' => 6]);

        return $this->render('commande/detail.html.twig', [
            'commandes' => $commandes,
            'client' => $user,
            'admin' => $admin
        ]);
    }

    /**
     * @Route("/commande", name="commande_list")
     */
    public function mesCommandes(){
        $admin = false;
        $user = $this->getUser();
        if($user != null){
            $roles = $user->getRoles();
            foreach ($roles as $role){
                if($role == "ROLE_ADMIN"){
                    $admin = true;
                    break;
                }
            }
        }
        else{
            return $this->redirectToRoute('app_login');
        }
        $em = $this->getDoctrine()->getManager();
        $commandesEnCours = $em->getRepository(Commande::class)->findCommandeEnCours($this->getUser()->getId());
        $commandesTerminer = $em->getRepository(Commande::class)->findCommandeTerminer($this->getUser());

        //dd($commandesEnCours);
        //dd($commandesTerminer);

        return $this->render('commande/listCommande.html.twig',[
            'commandesEnCours' => $commandesEnCours,
            'commandesTerminer' => $commandesTerminer,
            'admin' => $admin,
        ]);
    }

    /**
     * @Route("/commande/creneau/{id}", name="commande_creneau")
     * @param Request $request
     * @param Commande $commande
     * @return Response
     */
    public function commandeCreneau(Request $request, Commande $commande){
        $admin = false;
        $user = $this->getUser();
        if($user != null){
            $roles = $user->getRoles();
            foreach ($roles as $role){
                if($role == "ROLE_ADMIN"){
                    $admin = true;
                    break;
                }
            }
        }

        $em = $this->getDoctrine()->getManager();
        $creneaux = $commande->getMagasin()->getCreneauxDisponible();

        if($creneaux != null){



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
            'admin' => $admin,
        ]);
        }
        else{
            return $this->redirectToRoute('magasin_list');
        }
    }

    /**
     * @Route("/commande/gerer", name="commande_gerer")
     *
     */
    public function gererCommande(){
        $admin = false;
        $user = $this->getUser();
        if($user != null){
            $roles = $user->getRoles();
            foreach ($roles as $role){
                if($role == "ROLE_ADMIN"){
                    $admin = true;
                    break;
                }
            }
        }

        if($admin == false){
            return $this->redirectToRoute('magasin_list');
        }

        $em = $this->getDoctrine()->getManager();

        $commandes = $em->getRepository(Commande::class)->findBy([],['etat' => 'ASC']);

        return $this->render('commande/gerer-commande-list.html.twig',[
            'commandes' => $commandes,
            'admin' => $admin,
        ]);
    }

    /**
     * @Route("/commande/gerer/{id}", name="commande_gerer_etat")
     * @param Request $request
     * @param Commande $commande
     * @param Swift_Mailer $mailer
     * @return RedirectResponse|Response
     */
    public function gererEtatCommande(Request $request, Commande $commande, Swift_Mailer $mailer){
        $admin = false;
        $user = $this->getUser();
        if($user != null){
            $roles = $user->getRoles();
            foreach ($roles as $role){
                if($role == "ROLE_ADMIN"){
                    $admin = true;
                    break;
                }
            }
        }

        if($admin == false){
            return $this->redirectToRoute('magasin_list');
        }

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(CommandeType::class, $commande);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $commande->setEtat($form['etat']->getData());
            $em->persist($commande);
            $em->flush();
            if($form['etat']->getData()->getId() == 3){
                $message = new Swift_Message('Confirmation de commande');
                $message->setFrom('dev.lpmetinet@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody($this->renderView('commande/commande-prete-email.html.twig', [
                        'commande' => $commande
                    ]), 'text/html');

                $mailer->send($message);
            }
            return $this->redirectToRoute('commande_gerer');
        }

        return $this->render('commande/gerer-commande.html.twig',[
            'commande' => $commande,
            'form' => $form->createView(),
            'admin' => $admin,
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
        $commandes = $em->getRepository(Commande::class)->findBy(['client' => $user, 'etat' => 6]);

        foreach ($commandes as $commande) {
            foreach ($commande->getCommandeLignes() as $ligne) {
                $stock = $em->getRepository(Stocks::class)->findOneBy(['produit' => $ligne->getProduit(), 'magasin' => $commande->getMagasin()]);
                $stock->setQuantity($stock->getQuantity() - $ligne->getQuantity());
                $em->persist($stock);
            }

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

    /**
     * @Route("/commande/cancel/{id}", name="commande_cancel")
     * @param Commande $commande
     * @return RedirectResponse
     */
    public function cancel(Commande $commande) {
        $em = $this->getDoctrine()->getManager();

        $commande->setEtat($em->getRepository(CommandeStatut::class)->find(5));
        $em->persist($commande);
        $em->flush();

        return $this->redirectToRoute('commande_detail');
    }
}
