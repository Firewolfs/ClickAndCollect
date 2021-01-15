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
        $form = $this->createForm(StockType::class,null, ["addStock" => true] );

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $stock = new Stocks();
            $stock->setMagasin($magasin);
            $stock->setProduit($form['produit']->getData());
            $stock->setQuantity($form['quantity']->getData());
            $em->persist($stock);
            $em->flush();

            return $this->redirectToRoute('magasin_detail', ['id' => $stock->getMagasin()->getId()]);
        }

        return $this->render('stock/addStock.html.twig', [
            'form' => $form->createView(),
            'admin' => $admin
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
        $form = $this->createForm(StockType::class, $stock);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $stock->setQuantity($form['quantity']->getData());
            $em->persist($stock);
            $em->flush();

            return $this->redirectToRoute('magasin_detail', ['id' => $stock->getMagasin()->getId()]);
        }

        return $this->render('stock/editStock.html.twig', [
            'form' => $form->createView(),
            'admin' => $admin
        ]);
    }
}