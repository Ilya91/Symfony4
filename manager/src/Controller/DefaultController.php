<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\GiftService;
use App\Services\MyService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DefaultController extends AbstractController
{
    public $killer;

    public function __construct($logger)
    {
        $this->killer = $logger;
    }


    /**
     * @Route("/", name="default")
     * @param GiftService $giftService
     * @param MyService $service
     * @param ContainerInterface $container
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(GiftService $giftService, MyService $service, ContainerInterface $container): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
//        if (!$users){
//            throw $this->createNotFoundException('The users do not exist');
//        }
        dump($container->get('app.myservice'));
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'users' => $users,
            'random_gift' => $giftService->gifts
        ]);
    }

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


    /**
     * @Route("/generate-url/{param?}", name="generate_url")
     */
    public function generate_url(): void
    {
        exit($this->generateUrl(
            'generate_url',
            array('param' => 10),
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }


    /**
     * @Route("/download", name="download")
     */
    public function download()
    {
        $path = $this->getParameter('download_directory');
        return $this->file(($path.'text'));
    }

    /**
     * @Route("/forwarding-to-controller")
     */
    public function forwardingToController()
    {
        $response = $this->forward(
            'App\Controller\DefaultController::methodToForwardTo',
            ['param' => '1']
        );

        return $response;
    }

    /**
     * @Route("/url-to-forward-to/{param?}", name="route_to_forward_to")
     * @param $param
     */
    public function methodToForwardTo($param)
    {
        exit('Test controller forwarding - '. $param);
    }
}
