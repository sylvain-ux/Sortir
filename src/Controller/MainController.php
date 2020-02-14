<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\SearchType;
use App\Form\UserType;

use App\Entity\Trip;
use App\Form\UserUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class MainController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $entityManager,Request $request)
    {
        $tripRepository = $entityManager->getRepository(Trip::class);
        $allTrips = $tripRepository->findAll();

        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);

        return $this->render('trip/index.html.twig',compact('allTrips'));

    }



    //
//    /**
//     * @Route("/search2", name="search2")
//     */
//    public function search(Request $request, EntityManagerInterface $entityManager)
//    {
//        $searchForm = $this->createForm(SearchType::class);
//        $searchForm->handleRequest($request);
//
//        return $this->render(
//            'trip/index.html.twig',
//            [
//                'searchFormView' => $searchForm->createView(),
//            ]
//        );
//    }


}