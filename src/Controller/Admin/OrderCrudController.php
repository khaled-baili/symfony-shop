<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use function Sodium\add;

class OrderCrudController extends AbstractCrudController
{
    private $entityManager;
    private $curdUrlGenerator;
    public function __construct(EntityManagerInterface $entityManager, CrudUrlGenerator $crudUrlGenerator)
    {
        $this->entityManager=$entityManager;
        $this->curdUrlGenerator=$crudUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    public function configureActions(Actions $actions) : Actions {
        $updatePreparation = Action::new('updatePreparation','Préparation en cours')
            ->linkToCrudAction('updatePreparation');//ajout du boutton Préparation en cours
        $updateShipping = Action::new('updateShipping','En cours de livraison')
            ->linkToCrudAction('updateShipping');//ajout du boutton En cours de livraison
        return $actions
            ->add('detail',$updatePreparation)//defini l'action a executer
            ->add('detail',$updateShipping)//defini l'action a executer
            ->add('index', 'detail');
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id'=>'DESC']);//permet de trier els donnes selon l id de facon descendante dans la page du commande
    }
    /*methode permet de traiter le changement du statut du commande -- preparation en cours*/
    public function updatePreparation(AdminContext $context) {
        $order = $context->getEntity()->getInstance();
        $order->setState(2);
        $this->entityManager->flush();
        $this->addFlash('notice',"<span style='color: green'><strong>La commande<u>".$order->getReference()
            ."</u> est en cours de préparation</strong></span>");
        $url = $this->curdUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

        return $this->redirect($url);
    }
    /*methode permet de traiter le changement du statut du commande -- livraison en cours*/
    public function updateShipping(AdminContext $context) {
        $order = $context->getEntity()->getInstance();
        $order->setState(3);
        $this->entityManager->flush();
        $this->addFlash('notice',"<span style='color: green'><strong>La commande<u>".$order->getReference()
            ."</u>En cours de livraison</strong></span>");
        $url = $this->curdUrlGenerator->build()
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureFields(string $pageName): iterable
{
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'passé le'),
            TextField::new('user.getFullName', 'utilistaeur'),
            MoneyField::new('total')->setCurrency('EUR'),
            MoneyField::new('carrierPrice', 'frais de transport')->setCurrency('EUR'),
            BooleanField::new('isPaid'),
            ChoiceField::new('state')->setChoices([
                'Non payée' =>0,
                'Payée' => 1,
                'Préparation en cours' =>2,
                'Livraison en cours' => 3
            ]),
            ArrayField::new('orderDetails','Produits achetés')->hideOnIndex()
        ];
    }
}
