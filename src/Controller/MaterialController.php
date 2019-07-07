<?php

namespace App\Controller;

use App\Repository\MaterialAlgorithmRepository;
use App\Repository\MaterialProblemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class MaterialController extends AbstractController
{

    private $_materialProblemRepository;

    private $_materialAlgorithmRepository;

    private $_translator;

    /**
     * MaterialController constructor.
     *
     * @param MaterialProblemRepository $materialProblemRepository
     * @param MaterialAlgorithmRepository $materialAlgorithmRepository
     * @param TranslatorInterface $translator
     */
    public function __construct(
        MaterialProblemRepository $materialProblemRepository,
        MaterialAlgorithmRepository $materialAlgorithmRepository,
        TranslatorInterface $translator
    ) {
        $this->_materialProblemRepository = $materialProblemRepository;
        $this->_materialAlgorithmRepository = $materialAlgorithmRepository;
        $this->_translator = $translator;
    }

    public function index()
    {
        return $this->render('material/index.html.twig');
    }

    /**
     * Get Material Problems listing page.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function problemsList(Request $request)
    {
        $parameters = [];

        // Filter by tags.
        if ($tag = $request->query->get('tag')) {
            $parameters['tag'] = urldecode($tag);
        }

        return $this->render('material/problems.html.twig', [
            'problems' => $this->_materialProblemRepository->getList($parameters)
        ]);
    }

    /**
     * Get single Material Problem.
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function singleProblem(int $id)
    {
        $problem = $this->_materialProblemRepository
            ->find($id);

        if (null === $problem) {
            throw new NotFoundHttpException($this->_translator->trans('problem_not_found', ['%problem_id%' => $id]));
        }

        return $this->render('material/problem.html.twig', [
            'problem' => $problem
        ]);
    }

    /**
     * Get Material Algorithms listing page.
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function algorithmsList(Request $request)
    {
        $parameters = [];

        // Filter by tags.
        if ($tag = $request->query->get('tag')) {
            $parameters['tag'] = urldecode($tag);
        }

        return $this->render('material/algorithms.html.twig', [
            'algorithms' => $this->_materialAlgorithmRepository->getList($parameters)
        ]);
    }

}
