<?php

namespace App\Controller\ApiController;

use App\Entity\Magasin;
use App\Entity\Vendeur;
use App\Form\ContactType;
use App\Form\MagasinType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ApiMagasinController
 * @Route("/rest/api/magasin", name="api_magasin_")
 *
 * @package App\Controller\ApiController
 */
class ApiMagasinController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/list", name="list")
     */
    public function index(SerializerInterface $serializer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $magasins = $em->getRepository(Magasin::class)->findAll();

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
        $jsonResult = $serializer->serialize(
            ['magasin' => $magasins],
            'json',
            []
        );

        return new Response($jsonResult);
    }

    /**
     * @Route("/magasin/detail/{id}", name="magasin_detail")
     * @param Magasin $magasin
     * @return Response
     */
    public function detailMagasin(Magasin $magasin){
        $em = $this->getDoctrine()->getManager();

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

        return $this->render('magasin/detail.html.twig', [
            'magasin' => $magasin,
            'admin' => $admin,
        ]);
    }

    /**
     * @Route("magasin/add", name="magasin_add")
     * @param Request $request
     * @return Response
     */
    public function addMagasin(Request $request){
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
            return $this->redirectToRoute("magasin_list");
        }

        $em = $this->getDoctrine()->getManager();
        $addFrom = $this->createForm(MagasinType::class);
        $addFrom->handleRequest($request);
        if($addFrom->isSubmitted() && $addFrom->isValid()){
            $magasin = new Magasin();
            $magasin->setNom($addFrom['nom']->getData());
            $em->persist($magasin);
            $em->flush();
            return $this->redirectToRoute("magasin_list");
        }
        return $this->render('magasin/add.html.twig', [
            'form' => $addFrom->createView(),
            'admin' => $admin
        ]);
    }

    /**
     * @Route("magasin/edit/{id}", name="magasin_edit")
     */
    public function editMagasin(Request $request, Magasin $magasin){
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
            return $this->redirectToRoute("magasin_list");
        }
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createForm(MagasinType::class, $magasin);
        $editForm->handleRequest($request);

        if($editForm->isSubmitted() && $editForm->isValid()){
            $magasin->setNom($editForm['nom']->getData());
            $em->persist($magasin);
            $em->flush();
        }

        return $this->render('magasin/edit.html.twig', [
            'form' => $editForm->createView(),
            'admin' => $admin
        ]);
    }

    /**
     * @Route("magasin/delete/{id}", name="magasin_delete")
     * @param Magasin $magasin
     * @return RedirectResponse
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteMagasin(Magasin $magasin){
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
            return $this->redirectToRoute("magasin_list");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($magasin);
        $em->flush();
        return $this->redirectToRoute("magasin_list");
    }

    /**
     * @Route("/magasin/{id}/contact", name="magasin_contact")
     * @param Magasin $magasin
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return Response
     */
    public function contact(Magasin $magasin, Request $request, Swift_Mailer $mailer) {
        $em = $this->getDoctrine()->getManager();
        $vend = [];
        /** @var Vendeur[] $vendeurs */
        $vendeurs = $em->getRepository(Vendeur::class)->findBy(['magasin' => $magasin]);
        foreach ($vendeurs as $vendeur) {
            $vend[$vendeur->getNom() . ' ' . $vendeur->getPrenom()] = $vendeur->getId();
        }

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

        $form = $this->createForm(ContactType::class, null, ['vendeurs' => $vend]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = new Swift_Message('Contact');
            $message->setFrom($form->get('mail')->getData())
                ->setTo($em->getRepository(Vendeur::class)->find($form->get('vendeur')->getData())->getMail())
                ->setBody($this->renderView('magasin/contact-email.html.twig', [
                    'user' => $form->get('name')->getData() . ' ' . $form->get('firstname')->getData(),
                    'mail' => $form->get('mail')->getData(),
                    'message' => $form->get('message')->getData()
                ]), 'text/html');

            $mailer->send($message);
        }

        return $this->render('magasin/contact.html.twig', [
            'form' => $form->createView(),
            'admin' => $admin
        ]);
    }
}
