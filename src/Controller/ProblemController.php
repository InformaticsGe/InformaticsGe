<?php

namespace App\Controller;

use App\Repository\ProblemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProblemController extends AbstractController
{
    public function list(ProblemRepository $repository)
    {
        $problems = $repository->findVisibleForListing();

        return $this->render('problem/list.html.twig', [
            'problems' => $problems,
        ]);
    }
}
