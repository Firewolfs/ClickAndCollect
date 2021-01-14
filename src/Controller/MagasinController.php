<?php

namespace App\Controller;

use App\Entity\Magasin;
use App\Form\MagasinType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MagasinController extends AbstractController
{
    /**
     * @Route("/magasin", name="magasin_list")
     */
    public function index(): Response
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

        return $this->render('magasin/index.html.twig', [
            'magasins' => $magasins,
            'admin' => $admin,
        ]);
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
        ]);
    }

    /**
     * @Route("magasin/delete/{id}", name="magasin_delete")
     * @param Magasin $magasin
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
}
