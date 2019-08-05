<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\CartType;
use App\Entity\OrderCart;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartService extends AbstractController
{
	public function getCartItems()
    {
        $conn = $this->getDoctrine()->getManager()->getConnection();

        $sql = '
            SELECT c.id as cart_id, cp.id as cart_product_id, p.price * cp.quantity as subtotal, cp.quantity, cp.product_id, p.price, p.name, p.image, p.description
            FROM cart c
            left join cart_product cp
            on c.id = cp.cart_id
            join product p
            on cp.product_id = p.id
            WHERE c.user_id = :user_id AND c.type = :type
            ';

        $stmt = $conn->prepare($sql);
        $stmt->execute(['user_id' => 1, 'type' => 'order']);

        return $stmt->fetchAll();
    }

    /*
    * If we have authenticated users the logic will be
    * 1- checking if user cart is exists (we may find cart with a condition because our database design allows user 
    *    to have multiple carts in case we may want to keep old carts data, so the current cart may have a stuus of new 
    *    or something similar) || create new one
    * 2- adding items to the user cart
    *
    * The current senario will be
    * 1- I'll assume that our user holding id of 1 
    * 2- If the cart of the user does not exists create a new one
    * 3- check if the product already exists in the cart then => increase quantity
    * 4- add items to the cart
    */
    public function addItemToCart($product_id)
	{
		// get user cart
    	$user_cart = $this->getDoctrine()
    		->getRepository(OrderCart::class)
    		->findOneBy(['user_id' => 1]);

    	// init doctrine manager
    	$em = $this->getDoctrine()->getManager();

    	// check if cart not found then add new cart for the user
    	if (! $user_cart) {
    		$user_cart = new OrderCart();
    		$user_cart->setUserId(1);
    		$em->persist($user_cart);
    	}
    	
    	// get product
    	$product = $this->getDoctrine()
    		->getRepository(Product::class)
    		->find($product_id);

    	// check if product exists in cart_product table
    	$cart_product = $this->getDoctrine()
    		->getRepository(CartProduct::class)
    		->findOneBy(['product_id' => $product_id, 'cart_id' => $user_cart->getId()]);

    	if (! $cart_product) {
    		$cart_product = new CartProduct();
	    	$cart_product->setProduct($product);
	    	$cart_product->setCart($user_cart);
	    	$cart_product->setQuantity(1);
	    	$cart_product->setPrice($product->getPrice());
    	} else {
    		$cart_product->setQuantity($cart_product->getQuantity() + 1);
    	}
    	
    	$em->persist($cart_product);
    	$em->flush();
	}

    public function updateItem($request)
    {
        $cart_product_id = $request->request->get('cart_product_id');
        $quantity = $request->request->get('quantity');

        $em = $this->getDoctrine()->getManager();
        $cart_product = $em->getRepository(CartProduct::class)
            ->find($cart_product_id);

        if (! $cart_product) {
            throw $this->createNotFoundException(
                'Item not found'
            );
        }

        $cart_product->setQuantity($quantity);
        $em->flush();
    }

    public function deleteItem($cartProduct)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($cartProduct);
        $em->flush();
    }

    public function emptyCart()
    {
        $user_cart = $this->getDoctrine()
            ->getRepository(OrderCart::class)
            ->findOneBy(['user_id' => 1]);
        
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery('delete from App\Entity\CartProduct cp where cp.cart_id = ' . $user_cart->getId());
        $q->execute();
    }
}