<?php

namespace App\Controller;

use App\Repository\BlogRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class BlogController extends AbstractController
{
    private $_blogRepository;

    private $_translator;


    /**
     * BlogController constructor.
     *
     * @param BlogRepository      $blogRepository Blog repository.
     * @param TranslatorInterface $translator     Translator.
     */
    public function __construct(BlogRepository $blogRepository, TranslatorInterface $translator)
    {
        $this->_blogRepository = $blogRepository;
        $this->_translator = $translator;
    }

    /**
     * Blog list action.
     *
     * @param $_locale
     * @param int $page
     * @param PaginatorInterface $paginator
     * @param Request $request
     *
     * @return Response
     */
    public function list($_locale, int $page, PaginatorInterface $paginator, Request $request)
    {
        $queryBuilderParams = [];

        // Filter by tags.
        if ($tag = $request->query->get('tag')) {
            $queryBuilderParams['tag'] = urldecode($tag);
        }

        // Create pagination.
        $pagination = $paginator->paginate(
            $this->_blogRepository->getQueryBuilder($queryBuilderParams),
            $page,
            getenv('BLOG_COUNT')
        );

        return $this->render('blog/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Show single blog.
     *
     * @param $_locale
     * @param $id
     *
     * @return Response
     */
    public function single($_locale, $id)
    {
        $blog = $this->_blogRepository
            ->find($id);

        if (null === $blog) {
            throw new NotFoundHttpException($this->_translator->trans('blog_not_found', ['%blog_id%' => $id]));
        }

        return $this->render('blog/single.html.twig', [
            'blog' => $blog,
        ]);
    }
}
