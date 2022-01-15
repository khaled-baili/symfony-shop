<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/my_account", name="account")
     * retourner vers la paghe home du site
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig', [

        ]);
    }
}
