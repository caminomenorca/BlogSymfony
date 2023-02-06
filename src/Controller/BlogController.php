<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    
    #[Route('/publication/{id}', name: 'publication')]
    public function show(Request $request, Post $post, CommentRepository $commentRepository): Response
    {
    $offset = max(0, $request->query->getInt('offset', 0));
    $paginator = $commentRepository->getCommentPaginator($post, $offset);
    return $this->render('blog/show.html.twig', [
    'publication' => $post,
    'comments' => $paginator,
   'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
    'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
    ]);
    }

    #[Route('/', name: 'app_blog', defaults: ['page' => '1'], methods: ['GET'])]
    #[Route('/page/{page<[1-9]\d{0,8}>}', name: 'app_blog_page', methods: ['GET'])]
    public function index(int $page, PostRepository $posts): Response
    {
        $latestPosts = $posts->findAll();
        return $this->render('blog/index.html.twig', [
            'publications' => $latestPosts,
        ]);
    }


    /**
     */
    public function __construct()
    {
    }
}
