<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Carrier;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/order", name="order")
     */
    public function index(Cart $cart, Request $request): Response
    {
        if ($this->getUser()->getAdresses()->getValues()) {
            $this->redirectToRoute('add_address');
        }
        $form = $this->createForm(OrderType::class, null, [
            'user'=>$this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'form'=>$form->createView(),
            'cart'=>$cart->getFull()
        ] );
    }

    /**
     * @Route("/order/order-recap", name="order_recap",methods={"POST"} )
     */
    public function addOrder(Cart $cart, Request $request): Response
    {
        if ($this->getUser()->getAdresses()->getValues()) {
            $this->redirectToRoute('add_address');
        }
        $form = $this->createForm(OrderType::class, null, [
            'user'=>$this->getUser()
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTimeImmutable();
            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $carriers=$form->get('carriers')->getData();
            $delivery= $form->get('adresses')->getData();
            $delivery_content = $delivery->getFirstName().' '.$delivery->getLastName();
            if ($delivery->getCompany()) {
                $delivery_content .= '<br>'.$delivery->getCompany();
            }
            $delivery_content .= '<br>'.$delivery->getAdress();
            $delivery_content .= '<br>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .= '<br>'.$delivery->getCountry();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivrey($delivery_content);
            $order->setIsPaid(0);
            $this->entityManager->persist($order);

            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);
            }
            $this->entityManager->flush();
            return $this->render('order/add.html.twig', [
                'form'=>$form->createView(),
                'cart'=>$cart->getFull(),
                'carriers'=>$carriers,
                'delivery'=>$order->getDelivrey(),
                'reference' =>$order->getReference()
            ] );
        }
        return $this->redirectToRoute('cart');

    }
}
