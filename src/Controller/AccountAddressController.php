<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Adresse;
use App\Form\AdresseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/account/address", name="account_address")
     */
    public function index(): Response
    {
        return $this->render('account/address.html.twig', [
        ]);
    }
    /**
     * @Route("/account/add-address", name="add_address")
     */
    public function addAddress(Cart $cart,Request $request)
    {
        $address = new Adresse();
        $form = $this->createForm(AdresseType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            if ($cart->get()) {
                return $this->redirectToRoute('order');
            } else {
                return $this->redirectToRoute('account_address');
            }


        }
        return $this->render('account/address_add.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/account/modify-address/{id}", name="modify_address")
     */
    public function modifyAddress(Request $request, $id)
    {
        $address = $this->entityManager->getRepository(Adresse::class)->findOneById($id);
        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_address');
        }
        $form = $this->createForm(AdresseType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $this->entityManager->flush();
            return $this->redirectToRoute('account_address');

        }
        return $this->render('account/address_add.html.twig', [
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/account/remove-address/{id}", name="remove_address")
     */
    public function removeAddress(Request $request, $id)
    {
        $address = $this->entityManager->getRepository(Adresse::class)->findOneById($id);
        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_address');
        } else {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
            return $this->redirectToRoute('account_address');
        }
    }
}
