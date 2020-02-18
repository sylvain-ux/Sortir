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

            //fonction activé que si je clique sur le bouton 'Recherche'
            if ($searchForm->getClickedButton() === $searchForm->get('save')) {

                //Recherche en fonction des ville organisatrice :
                $school = $searchForm->get('site')->getData();
                if ($school != null) {
                    $schoolId = $school->getId();
                    $allTrips = $tripRepository->findBySchoolID($schoolId);
                }

                //Recherche pour afficher les sorties dont je suis l'organisatreur/trice :
                $organizer = $searchForm->get('TripOrganizer')->getData();
                if ($organizer != null) {
                    //Mon id connecté
                    $myId = $this->getUser();
                    $allTrips = $tripRepository->findByMyTrip($myId);
                }

                //Recherche pour afficher les sorties passées :
                $pastTrip = $searchForm->get('TripPast')->getData();
                if ($pastTrip != null) {
                    $allTrips = $tripRepository->findByPastTrip();
                }

                // Recherche en fonction des dates :
                // Date de debut :
                $dateStart = $searchForm->get('dateStart')->getData();
                if ($dateStart != null) {
                    $allTrips = $tripRepository->findByDateStart($dateStart);
                }

                // Date de fin :
                $dateEnd = $searchForm->get('dateEnd')->getData();
                if ($dateEnd != null) {
                    $allTrips = $tripRepository->findByDateEnd($dateEnd);
                }

                //Recherche pour afficher les sorties auxquelles je suis inscrit/e :
                $myTrip = $searchForm->get('TripRegistered')->getData();
                if ($myTrip != null) {
                    //Mon id connecté
                    $myId = $this->getUser();
                    $allTrips = $tripRepository->findByMyRegistration($myId);
                }
                //Recherche pour afficher les sorties auxquelles je ne suis pas inscrit/e :
                $myNotTrip = $searchForm->get('TripNotRegistered')->getData();
                if ($myNotTrip != null) {
                    //Mon id connecté
                    $myId = $this->getUser();
                    $allTrips = $tripRepository->findByMyRegistration($myId);
                }
            }
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