<?php

namespace App\Controller;

use App\Repository\MaterialProblemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MaterialController extends AbstractController
{

    public function index()
    {
        return $this->render('material/index.html.twig');
    }

    public function problemsList(MaterialProblemRepository $repository, Request $request)
    {
        $parameters = [];

        // Filter by tags.
        if ($tag = $request->query->get('tag')) {
            $parameters['tag'] = urldecode($tag);
        }

        return $this->render('material/problems.html.twig', [
            'problems' => $repository->getList($parameters)
        ]);
    }

}
