<?php

namespace App\DataFixtures;

use App\Entity\NormalProduct;
use App\Entity\SaleProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
        	$product = new NormalProduct();
        	$product->setName('normal product ' . $i);
        	$product->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet numquam aspernatur!');
        	$product->setPrice(mt_rand(10, 100));
        	$product->setImage('700x400.png');
        	$product->setInStock(mt_rand(1, 20));
        	$manager->persist($product);
        }

        for ($i = 0; $i < 10; $i++) {
            $product = new SaleProduct();
            $product->setName('sale product ' . $i);
            $product->setDescription('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet numquam aspernatur!');
            $product->setPrice(mt_rand(10, 100));
            $product->setImage('700x400.png');
            $product->setInStock(mt_rand(1, 20));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
