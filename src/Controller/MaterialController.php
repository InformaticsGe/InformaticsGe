<?php

namespace App\Controller;

use App\Repository\MaterialProblemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class MaterialController extends AbstractController
{

    private $_repository;

    private $_translator;

    /**
     * MaterialController constructor.
     *
     * @param MaterialProblemRepository $repository
     * @param TranslatorInterface $translator
     */
    public function __construct(MaterialProblemRepository $repository, TranslatorInterface $translator)
    {
        $this->_repository = $repository;
        $this->_translator = $translator;
    }

    public function index()
    {
        return $this->render('material/index.html.twig');
    }

    public function problemsList(Request $request)
    {
        $parameters = [];

        // Filter by tags.
        if ($tag = $request->query->get('tag')) {
            $parameters['tag'] = urldecode($tag);
        }

        return $this->render('material/problems.html.twig', [
            'problems' => $this->_repository->getList($parameters)
        ]);
    }

    public function singleProblem(int $id)
    {
        $problem = $this->_repository
            ->find($id);

        if (null === $problem) {
            throw new NotFoundHttpException($this->_translator->trans('problem_not_found', ['%problem_id%' => $id]));
        }

        return $this->render('material/problem.html.twig', [
            'problem' => $problem
        ]);
    }

}
