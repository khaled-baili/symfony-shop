<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager= $entityManager;
    }
    /**
     * @Route("/cart", name="cart")
     */
    public function index(Cart $cart)
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add(Cart $cart, $id)
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }
    /**
     * @Route("/cart/reomove", name="remove_my_cart")
     */
    public function remove(Cart $cart)
    {
        $cart->remove();
        return $this->redirectToRoute('products');
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_product_cart")
     */
    public function delete(Cart $cart, $id)
    {
        $cart->delete($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/addqte/{id}", name="add_qte_to_cart")
     */
    public function addQte(Cart $cart, $id)
    {
        $cart->addQte($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/decqte/{id}", name="dec_qte_to_cart")
     */
    public function decQte(Cart $cart, $id)
    {
        $cart->decQte($id);
        return $this->redirectToRoute('cart');
    }
}
