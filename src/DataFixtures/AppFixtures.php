<?php

namespace App\DataFixtures;

use App\Entity\CartType;
use App\Entity\Product;
use App\Entity\ProductType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cartType1 = new CartType();
        $cartType1->setName('Order');
        $manager->persist($cartType1);

        $cartType2 = new CartType();
        $cartType2->setName('Wish-list');
        $manager->persist($cartType2);

        $productType1 = new ProductType();
        $productType1->setName('Normal');
        $manager->persist($productType1);

        $productType2 = new ProductType();
        $productType2->setName('Sale');
        $manager->persist($productType2);

        for ($i = 0; $i < 10; $i++) {
        	$product = new Product();
        	$product->setName('product ' . $i);
        	$product->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet numquam aspernatur!');
        	$product->setPrice(mt_rand(10, 100));
        	$product->setImage('700x400.png');
        	$product->setInStock(mt_rand(1, 20));
            $product->setType($productType1);
        	$manager->persist($product);
        }

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName('product ' . $i);
            $product->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet numquam aspernatur!');
            $product->setPrice(mt_rand(10, 100));
            $product->setImage('700x400.png');
            $product->setInStock(mt_rand(1, 20));
            $product->setType($productType2);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
