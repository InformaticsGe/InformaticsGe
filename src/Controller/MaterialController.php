<?php

namespace App\Controller;

use App\Repository\MaterialProblemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MaterialController extends AbstractController
{

    public function index()
    {
        return $this->render('material/index.html.twig');
    }

    public function problemsList(MaterialProblemRepository $repository)
    {
        $problems = $repository->findAll();

        return $this->render('material/problems.html.twig', [
            'problems' => $problems
        ]);
    }

}
