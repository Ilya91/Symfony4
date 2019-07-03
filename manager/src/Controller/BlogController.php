<?php

namespace App\Controller;

use App\Entity\Post;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $post = new Post();
        $date = new DateTime();
        $post->setCreatedAt($date);
        $post->setUpdatedAt($date);
        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class)
            ->add('user_id', IntegerType::class)
            ->add('content', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Post'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($post);
             $entityManager->flush();

            return $this->redirectToRoute('blog');
        }

        return $this->render('blog/new.html.twig', [
            'form' => $form->createView(),
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
