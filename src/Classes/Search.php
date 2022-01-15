<?php
namespace App\Classes;

use App\Entity\Category;
/*Classe associé au filtre de produit dans la page home*/
class Search {
    /**
     * @var string
     */
    public $string ='';

    /**
     * @var Category
     */
    public $categories = [];
}