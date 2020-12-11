<?php

namespace App\Controller;

use App\Entity\Magasin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        return $this->render('magasin/index.html.twig', [
            'magasins' => $magasins
        ]);
    }

    /**
     * @Route("/magasin/detail/{id}", name="magasin_detail")
     * @param Magasin $magasin
     */
    public function detailMagasin(Magasin $magasin){

    }

    /**
     * @Route("magasin/add", name="magasin_add")
     */
    public function addMagasin(){
        return $this->render('magasin/add.html.twig', [

        ]);
    }

    /**
     * @Route("magasin/edit/{id}", name="magasin_edit")
     */
    public function editMagasin(){
        return $this->render('magasin/edit.html.twig', [

        ]);
    }

    /**
     * @Route("magasin/delete/{id}", name="magasin_delete")
     * @param Magasin $magasin
     */
    public function deleteMagasin(Magasin $magasin){
        $em = $this->getDoctrine()->getManager();
        $em->remove($magasin);
        return $this->redirectToRoute("magasin_list");
    }
}
