<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
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
        $orders[]= $this->entityManager->getRespository(Order::class)->findAll();
        dd($orders);
        return $this->render('my_orders/index.html.twig', [
            'controller_name' => 'MyOrdersController',
        ]);
    }
}
