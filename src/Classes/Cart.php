<?php

namespace App\Classes;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
/*C'est une classe associé a la panier qui permet de definir la structure de cette derniere aussi permet
le stockage de la panier dans de le local storage du utilistaeur */
class Cart
{
    private $session; /*atrribut qu'on va l'utiliser pour la session*/
    private $entityManager; /*on l'va l'utliser pour la manipulation des methode de la base de données*/
    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager) {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    /* cette methode permet d'incrementer l'id de l'element de la panier dans la session */
    public function add($id) {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }
    /* permet de retourner la panier - contenu*/
    public function get() {
        return $this->session->get('cart');
    }
    /*permet de cider la panier*/
    public function remove() {
        return $this->session->remove('cart');
    }
    /*permet de supprimer un element bien spécifique de la panier selon l'id*/
    public function delete($id) {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);
        return $this->session->set('cart', $cart);

    }
    /*permet d'incrementer la valeur de quantité d'un element bien sécifique selon l'id*/
    public function addQte($id) {
        $cart = $this->session->get('cart', []);
        $cart[$id]++;
        $this->session->set('cart', $cart);
    }
    /*permet de décrementer la valeur de quantité d'un element bien sécifique selon l'id*/
    public function decQte($id) {
        $cart = $this->session->get('cart', []);
        if ($cart[$id]>1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        $this->session->set('cart', $cart);
    }
    /*Permet de retourner la vue complete pour un produit stocker dans le panier*/
    public function getFull() {
        $cartComplete = [];
        if ($this->get()) {
            foreach($this->get() as $id => $quantity) {
                $product_item = $this->entityManager->getRepository(Product::class)->findOneById($id);
                if (!$product_item) {
                    $this->delete($id);
                    continue;
                } else {
                    $cartComplete[] = [
                        'product' => $product_item,
                        'quantity' => $quantity
                    ];
                }
            }
        }
        return $cartComplete;
    }
}