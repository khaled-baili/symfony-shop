<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CarrierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }

    /*Permet de specifier les champs du formulaire pour la gestion de transporteur*/
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Name'),
            TextareaField::new('Description'),
            MoneyField::new('Price')->setCurrency('EUR'),
        ];
    }
}
