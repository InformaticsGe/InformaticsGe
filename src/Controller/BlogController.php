<?php

namespace App\Controller;

use App\Repository\BlogRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    private $_blogRepository;


    /**
     * BlogController constructor.
     *
     * @param BlogRepository $blogRepository Blog repository.
     */
    public function __construct(BlogRepository $blogRepository)
    {
        $this->_blogRepository = $blogRepository;
    }

    /**
     * Blog list action.
     *
     * @param $_locale
     * @param int $page
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function list($_locale, int $page, PaginatorInterface $paginator)
    {
        // Create pagination.
        $pagination = $paginator->paginate(
            $this->_blogRepository->getQueryBuilder(),
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

        return $this->render('blog/single.html.twig', [
            'blog' => $blog,
        ]);
    }
}
