<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
        //dump($posts);
        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/blog/new", name="blog_new")
     */
    public function new()
    {
        return $this->render('blog/new.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_view")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function view(Request $request)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($request->get('id'));
        return $this->render('blog/view.html.twig', [
            'post' => $post,
        ]);
    }
}
