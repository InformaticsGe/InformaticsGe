<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MaterialController extends AbstractController
{

    public function index()
    {
        return $this->render('material/index.html.twig');
    }

}
