<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Classes\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderValidateController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/order/success/{stripeSessionId}", name="order_validate")
     */
    public function index($stripeSessionId, Cart $cart): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if (!$order || $order->getUser() !=$this->getUser()) {
            return $this->redirectToRoute('home');
        }
        if(!$order->getIsPaid()) {
            $cart->remove();
            $order->setIsPaid(1);
            $order->setState(1);
            $this->entityManager->flush();
            $email = new Mail();
            $email_content = "Bonjour ".$order->getUser()->getFirstName().
                "<br/> Merci de votre confiance a notre boutique et nous espérons que vous serez satisfait par notre 
                qualité de produit";
            $email->send(
                $order->getUser()->getEmail(),
                $order->getUser()->getFirstName(),
                "Confirmation de paiement",
                $email_content
            );
        }
        return $this->render('order_validate/index.html.twig', [
            'order'=>$order
        ]);
    }

}
