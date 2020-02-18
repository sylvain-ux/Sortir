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

//            //Recherche en fonction des ville organisatrice :
//            $school = $searchForm->get('site')->getData();
//            if ($school != null) {
//                $schoolId = $school->getId();
//                $allTrips = $tripRepository->findBySchoolID($schoolId);
//            }
//
//            // Recherche en fonction des dates :
//
//            //Recherche pour afficher les sorties dont je suis l'organisatreur/trice :
//            //Mon id connecté
//            $myId = $this->getUser();
//
//            if ($myId != null) {
//                $allTrips = $tripRepository->findByMyTrip($myId);
//            }

            //Recherche pour afficher les sorties auxquelles je suis inscrit/e :
            //Mon id connecté
            $myId = $this->getUser();

            if ($myId != null) {
                $allTrips = $tripRepository->findByMyRegistration($myId);
            }


            //Recherche pour afficher les sorties auxquelles je ne suis pas inscrit/e :

            //Recherche pour afficher les sorties passées :
//            $allTrips = $tripRepository->findByPastTrip();

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