<?php

namespace App\Controller;

use App\Classes\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $enityManager)
    {
        $this->entityManager = $enityManager;
    }

    /**
     * @Route("/products", name="products")
     * methode permet de traiter la valeur de recherche et retourner les produits
     */
    public function index(Request $request): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        $search = new Search();
        $form = $this->createForm(SearchType::class,$search);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
        }
        return $this->render('product/index.html.twig', [
            'products'=>$products,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route ("product/{slug}", name="product")
     * methode permet de retourner les valeurs d'un produit specifique
     */
    public function show($slug)
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);
        if (!$product) {
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig', [
            'product'=>$product,
            'products'=>$products
        ]);
    }


}
