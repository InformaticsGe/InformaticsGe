<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    /**
     * About page action.
     *
     * @return Response
     */
    public function about()
    {
        return $this->render('default/about.html.twig');
    }

    /**
     * Compiler page action.
     *
     * @return Response
     */
    public function compiler()
    {
        return $this->render('default/compiler.html.twig');
    }
}
