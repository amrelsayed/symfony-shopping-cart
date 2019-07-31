<?php

namespace App\Controller;

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
            ->getRepository(Product::class)
            ->findBy(['type' => ProductType::TYPES['NORMAL']]);
        
        return $this->render('products/index.html.twig', [
        	'products' => $products
        ]);
    }
}
