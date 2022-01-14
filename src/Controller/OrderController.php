<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Carrier;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

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
            $products_for_stripe = [];
            $YOUR_DOMAIN = 'http://localhost:8000';
            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails);
                $products_for_stripe[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => ($product['product']->getPrice())/100,
                        'product_data' => [
                            'name' => $product['product']->getName(),
                            'images' => [$YOUR_DOMAIN."/uploads/".$product['product']->getIllustration()],
                        ],
                    ],
                    'quantity'=>$product['quantity'],
                ];
            }
            //$this->entityManager->flush();
            Stripe::setApiKey('sk_test_51KHaL0BZ5T7LnMPfVcmJS6P9tuSZfDGcuQ3RkJ1UP2WXKyXEioozOC3ukXU0PfPfryC4NRzwqK8Ada7l6Vu8ldMM00z2xcCtRq');
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    $products_for_stripe
                ],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/success.html',
                'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
            ]);
            return $this->render('order/add.html.twig', [
                'form'=>$form->createView(),
                'cart'=>$cart->getFull(),
                'carriers'=>$carriers,
                'delivery'=>$order->getDelivrey(),
                'stripe_checkout_session' => $checkout_session->id
            ] );
        }
        return $this->redirectToRoute('cart');

    }
}
