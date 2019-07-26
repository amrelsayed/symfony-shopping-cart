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
*
* There are many queries here and controller logic that should not be there
* it should be extracted to services or repositories, and there are get methods where I should
* use post, I'll try to refactor and find best practice for symfony if I've time
*/

namespace App\Controller;

use App\Entity\CartProduct;
use App\Services\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
    	$this->cartService = $cartService;
    }
    
    /**
	* @Route("/cart", name="cart")
	*/
    public function index()
    {
        $cart_items = $this->cartService->getCartItems();

        return $this->render('cart/index.html.twig', [
            'cart_items' => $cart_items,
        ]);
    }

    /**
    * @Route("/add-to-cart/{product_id}", name="add_to_cart")
    */
    public function addItemToCart($product_id)
    {
    	$this->cartService->addItemToCart($product_id);
    	
    	return $this->redirectToRoute('cart');
    }

	/**
    * @Route("/update-cart-item", name="update_cart_item", methods={"POST"})
    */    
    public function updateItemQuantity(Request $request)
    {
    	$this->cartService->updateItem($request);
    	
    	$this->addFlash('success', 'Item updated successfuly');
    	return $this->redirectToRoute('cart');
    }

    /**
    * @Route("/delete-cart-item/{id}", name="delete_cart_item")
    */
    public function deleteItem(CartProduct $cartProduct)
    {
    	$this->cartService->deleteItem($cartProduct);

    	$this->addFlash('success', 'Item deleted successfuly');
    	return $this->redirectToRoute('cart');
    }

    /**
    * @Route("empty-cart", name="empty_cart")
    */
    public function emptyCart()
    {
    	$this->cartService->emptyCart();

    	return $this->redirectToRoute('cart');
    }
}
