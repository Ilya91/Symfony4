<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\GiftService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     * @param GiftService $giftService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(GiftService $giftService)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        dump($users);
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'users' => $users,
            'random_gift' => $giftService->gifts
        ]);
    }

    /**
     * @Route("/blog/{page?}", name="blog_list", requirements={"page"="\d+"})
     * @param Request $request
     * @param SessionInterface $session
     */
    public function blog(Request $request, SessionInterface $session)
    {
        //exit($request->cookies->get('PHPSESSID'));
        $session->set('name', 'session_val');
        $cookie = new Cookie(
            'my_cookie',
            'cookie_value',
            time() + (2 * 365 * 24 * 60 * 60)
        );
        $this->addFlash('notice', 'You logged in!');
        $res = new Response();
        $res->headers->setCookie($cookie);
        $res->send();



        //return $this->render('default/blog.html.twig', []);
    }
}
