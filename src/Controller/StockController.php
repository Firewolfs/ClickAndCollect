<?php


namespace App\Controller;

use App\Entity\Magasin;
use App\Entity\Stocks;
use App\Form\StockType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StockController extends AbstractController
{
    /**
     * @Route("magasin/stock/add/{id}", name="magasin_stock_add")
     */
    public function addStock(Request $request, Magasin $magasin):Response
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(StockType::class,null, ["addStock" => true] );

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $stock = new Stocks();
            $stock->setMagasin($magasin);
            $stock->setProduit($form['produit']->getData());
            $stock->setQuantity($form['quantity']->getData());
            $em->persist($stock);
            $em->flush();
        }
        return $this->render('stock/addStock.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/magasin/stock/edit/{id}", name="magasin_stock")
     * @param Stocks $stock
     * @param Request $request
     * @return Response
     */
    public function editStock(Request $request, Stocks $stock):Response
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(StockType::class, $stock);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $stock->setQuantity($form['quantity']->getData());
            $em->persist($stock);
            $em->flush();
        }
        return $this->render('stock/editStock.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}