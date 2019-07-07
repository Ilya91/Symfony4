<?php

namespace App\Controller;

use App\Entity\Post;
use DateTime;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $queryBuilder = $this->getDoctrine()->getRepository(Post::class)->findPostsWithAuthors();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('blog/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/blog/new", name="blog_new")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws Exception
     */
    public function new(Request $request)
    {
        $post = new Post();
        $date = new DateTime();
        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Post'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreatedAt($date);
            $post->setUpdatedAt($date);
            $post->setUser($this->getUser());
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($post);
             $entityManager->flush();

            $this->addFlash(
                'success',
                'New post created successfully!'
            );

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
     * @return Response
     */
    public function view(Request $request)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($request->get('id'));
        return $this->render('blog/view.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/blog/{id}/delete", name="blog_delete")
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($request->get('id'));
        if ($post){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();

            $this->addFlash(
                'danger',
                $post->getTitle() . ' was deleted'
            );

            return $this->redirectToRoute('blog');
        }
    }

    /**
     * @Route("/blog/{id}/update", name="blog_update")
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->find($request->get('id'));

        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('save', SubmitType::class, ['label' => 'Update Post'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($post);
            $em->flush();

            $this->addFlash(
                'success',
                'Post was successfully updated!'
            );

            return $this->redirectToRoute('blog');
        }

        return $this->render('blog/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
