<?php

namespace App\Controller;

use App\Form\PasswordResetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/account/modify-password", name="account_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notif = null;
        $user = $this->getUser();
        $form = $this->createForm(PasswordResetType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();
            if($encoder->isPasswordValid($user, $old_pwd)) {
                $new_pwd = $form->get('new_password')->getData();
                $password = $encoder->encodePassword($user, $new_pwd);
                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $notif="votre mot de passe est modifé";
            } else $notif="le mot de passe ce n est pas celui indiqué";
        }
        return $this->render('account/password.html.twig', [
            'form'=> $form->createView(),
            'notif' => $notif
        ]);
    }
    
}
