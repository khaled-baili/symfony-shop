<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Reference;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyOrdersController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/order/my-orders", name="my_orders")
     */
    public function index(): Response
    {

        $orders= $this->entityManager->getRepository(Order::class)->findSuccessOrders($this->getUser());
        return $this->render('my_orders/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/order/my-orders/{reference}", name="my_order_details")
     */
    public function showDetails($reference)
    {

        $order= $this->entityManager->getRepository(Order::class)->findOneByReference($reference);
        if (!$order ||$order->getUser()!=$this->getUser()) {
            return $this->redirectToRoute('my_orders');
        }
        return $this->render('my_orders/orderDetails.html.twig', [
            'order' => $order,
        ]);
    }
}
