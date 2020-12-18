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
     * @Route("/", name="magasin_list")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $magasins = $em->getRepository(Magasin::class)->findAll();

        return $this->render('magasin/index.html.twig', [
            'magasins' => $magasins
        ]);
    }

    /**
     * @Route("/magasin/detail/{id}", name="magasin_detail")
     * @param Magasin $magasin
     * @return Response
     */
    public function detailMagasin(Magasin $magasin){
        $em = $this->getDoctrine()->getManager();

        return $this->render('magasin/detail.html.twig', [
            'magasin' => $magasin
        ]);
    }

    /**
     * @Route("magasin/add", name="magasin_add")
     * @param Request $request
     * @return Response
     */
    public function addMagasin(Request $request){
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
     */
    public function deleteMagasin(Magasin $magasin){
        $em = $this->getDoctrine()->getManager();
        $em->remove($magasin);
        $em->flush();
        return $this->redirectToRoute("magasin_list");
    }
}
