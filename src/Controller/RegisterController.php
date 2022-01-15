<?php

namespace App\Controller;

use App\Classes\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use PharIo\Manifest\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notif = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if (!$search_email) {
                $password = $encoder->encodePassword($user,$user->getPassword());
                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $email = new Mail();
                $email_content = "Bonjour ".$user->getFirstName().
                    "<br/> Bienvenue dans notre boutique et nous vous garantions votre satistfaction";
                $email->send(
                    $user->getEmail(),
                    $user->getFirstName(),
                    "Confirmation inscription",
                    $email_content
                );
                $notif="Votre inscription s'est correctement déroulé, 
                ous pouvez maintenant se connecter a votre compte";
            } else {
                $notif="Votre email existe deja";
            }

        }
        return $this->render(
            'register/index.html.twig',
            [
                'form' => $form->createView(),
                'notif' => $notif
            ]
        );
    }
}
