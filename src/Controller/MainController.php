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

class MainController extends AbstractController
{

    /**
     * @Route("/", name="home", requirements={"id" : "\d+"})
     */
    public function index(EntityManagerInterface $entityManager, Request $request, $id = 0)
    {
        $tripRepository = $entityManager->getRepository(Trip::class);
        $allTrips = $tripRepository->findAll();

        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            //ENORME FONCTION

            $schoolId = $searchForm->get('site')->getData();
            $dateStartId = $searchForm->get('dateStart')->getData();
            $dateEndId = $searchForm->get('dateEnd')->getData();
            //Mon id connecté
            $organizerId = $searchForm->get('TripOrganizer')->getData();
            $myRegistrationId = $searchForm->get('TripRegistered')->getData();
            $myNotRegistrationId = $searchForm->get('TripNotRegistered')->getData();
            $pastTrip = $searchForm->get('TripPast')->getData();

            $allTrips = $tripRepository->findFilters(
                $schoolId,
                $dateStartId,
                $dateEndId,
                $organizerId,
                $myRegistrationId,
                $myNotRegistrationId,
                $pastTrip,
                $request,
                $this->getUser()
            );
        }

        return $this->render(
            'trip/index.html.twig',
            ['allTrips' => $allTrips, 'searchFormView' => $searchForm->createView()]
        );

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