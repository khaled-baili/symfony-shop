<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    /**
     * @Route("/order/create-session/{reference}", name="stripe_create_session")
     */
    public function index(EntityManagerInterface $entityManager, $reference): Response
    {
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://localhost:8000';
        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);
        if(!$order) {
            new JsonResponse(['error'=>'order']);
        }
        foreach ($order->getOrderDetails()->getValues() as $product) {
            $product_obj = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => ($product->getPrice()),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN."/uploads/".$product_obj->getIllustration()],
                    ],
                ],
                'quantity'=>$product->getQuantity(),
            ];
        }
        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => ($order->getCarrierPrice()*100),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity'=>1,
        ];
        Stripe::setApiKey('sk_test_51KHaL0BZ5T7LnMPfVcmJS6P9tuSZfDGcuQ3RkJ1UP2WXKyXEioozOC3ukXU0PfPfryC4NRzwqK8Ada7l6Vu8ldMM00z2xcCtRq');
        $checkout_session = Session::create([
            'customer_email'=>$this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $products_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/order/success/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/order/cancel/{CHECKOUT_SESSION_ID}',
        ]);
        $checkout_id = $checkout_session->id;
        $order->setStripeSessionId($checkout_id);
        $entityManager->flush();
        $response = new JsonResponse(['id'=> $checkout_id]);
        return $response;
    }
}
