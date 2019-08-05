<?php

namespace App\Controller;

use App\Entity\NormalProduct;
use App\Entity\Product;
use App\Entity\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $products = $this->getDoctrine()
            ->getRepository(NormalProduct::class)
            ->findAll();
        
        return $this->render('products/index.html.twig', [
        	'products' => $products
        ]);
    }
}
