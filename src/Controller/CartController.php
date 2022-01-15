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
     * methode permet de retourner la vue complete de la panier --> produit -->qte
     */
    public function index(Cart $cart)
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     * methode permet l'ajout d'un produit dans le panier
     */
    public function add(Cart $cart, $id)
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }
    /**
     * @Route("/cart/reomove", name="remove_my_cart")
     * methode permet de supprimer un tout le panier
     */
    public function remove(Cart $cart)
    {
        $cart->remove();
        return $this->redirectToRoute('products');
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_product_cart")
     * methode permet de suprimer un element specifique de panier selon l'id
     */
    public function delete(Cart $cart, $id)
    {
        $cart->delete($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/addqte/{id}", name="add_qte_to_cart")
     * methode permet d'incrementer la valeur de la quantité de la panier
     */
    public function addQte(Cart $cart, $id)
    {
        $cart->addQte($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/decqte/{id}", name="dec_qte_to_cart")
     * methode permet de decrementer la valeur de la quantiyé de panier
     */
    public function decQte(Cart $cart, $id)
    {
        $cart->decQte($id);
        return $this->redirectToRoute('cart');
    }
}
