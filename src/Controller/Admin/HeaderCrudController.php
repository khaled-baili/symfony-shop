<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }
    /*Configurer les champs de la gestion du header -- slide du page home*/
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title','Titre de header'),
            TextField::new('content', 'contenu de notre header'),
            TextField::new('btnTitle','Titre de notre bouton'),
            TextField::new('btnUrl','Url de destination de notre bouton'),
            ImageField::new('ullustration')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
                ->setFormTypeOptions(['data_class'=>null]),//ajout de l'image
        ];
    }

}
