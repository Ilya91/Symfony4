<?php


namespace App\Controller;


use App\Entity\User;
use App\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{

    /**
     * @Route("/video", name="video")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = [];
        for ($i=1; $i <= 3; $i++){
            $user = $em->getRepository(User::class)->find($i);
            $video = new Video();
            $video->setTitle('Video title - ' . $i);
            $user->addVideo($video);
            $em->persist($video);
            $em->flush();
            $users[] = $video->getUser()->getUsername();
        }
        dump($users);
        return $this->render('video/index.html.twig', [

        ]);
    }
}