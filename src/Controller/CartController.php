<?php

/**
* The Cart itself is not considered an order yet So, we may sotre it locally 
* in cookies or in local storage in mobile apps this will make users add products 
* to cart easly even without authentication, but if we have multiple frontends
* and we want to display the same cart for the authenticated user on mobile and 
* web for example. So, storing cart in the database will be very helpful.
*
* The next step is considereing the cart an Order this will happen after checkout 
* and confirming the order details (choose shipping, calculating discounts, ...etc)
* in that case I think we have two options to keep track of order products and order details
* 1- we may add a cart_id in order table since we have this information aleardy in the cart
* 2- or we may generate a serialized object or a json object and store it in the order table.
*
* Since this project is for testing and playing around symfony and we are doing just shopping cart,
* I will consider that we have one user and one cart
*/

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
	* @Route("/cart", name="cart")
	*/
    public function index()
    {
        $cart_items = $this->getDoctrine()
    		->getRepository(Cart::class)
    		->findCartProductsByUserId(1);

        return $this->render('cart/index.html.twig', [
            'cart_items' => $cart_items,
        ]);
    }

    /**
    * @Route("/add-to-cart/{product_id}", name="add_to_cart")
    */
    public function addItemToCart($product_id)
    {
    	/*
    	* If we have authenticated users the logic will be
    	* 1- checking if user cart is exists (we may find cart with a condition because our database design allows user 
    	* 	 to have multiple carts in case we may want to keep old carts data, so the current cart may have a stuus of new 
    	* 	 or something similar) || create new one
    	* 2- adding items to the user cart
		*
    	* The current senario will be
    	* 1- I'll assume that our user holding id of 1 
    	* 2- If the cart of the user does not exists create a new one
    	* 3- check if the product already exists in the cart then => increase quantity
    	* 4- add items to the cart
    	*/

    	// get user cart
    	$user_cart = $this->getDoctrine()
    		->getRepository(Cart::class)
    		->findOneBy(['user_id' => 1]);

    	// init doctrine manager
    	$em = $this->getDoctrine()->getManager();

    	// check if cart not found then add new cart for the user
    	if (! $user_cart) {
    		$user_cart = new Cart();
    		$user_cart->setUserId(1);
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
    	
    	$user_cart->setTotalPrice($user_cart->getTotalPrice() + $product->getPrice());
    	
    	$em->persist($user_cart);
    	$em->persist($cart_product);
    	$em->flush();
    	
    	return $this->redirectToRoute('cart');
    }

    /**
    * @Route("/delete-cart-item/{id}", name="delete_cart_item")
    */
    public function deleteItem(CartProduct $cartProduct)
    {
    	$em = $this->getDoctrine()->getManager();

    	$em->remove($cartProduct);
    	$em->flush();

    	$this->addFlash('success', 'Item deleted successfuly');
    	return $this->redirectToRoute('cart');
    }
}
