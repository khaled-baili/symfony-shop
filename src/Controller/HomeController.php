<?php

namespace App\Controller;

use App\Classes\Mail;
use App\Entity\Header;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    /**
     * @Route("/", name="home")
     * methode permet de retourner les produits selectionner isbest par l'admin
     * retourner les donnees a utiliser dans le header dans la pâge home
     */
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);
        $headers = $this->entityManager->getRepository(Header::class)->findAll();
        return $this->render('home/index.html.twig', [
            'products'=>$products,
            'headers'=>$headers
        ]);
    }
}
