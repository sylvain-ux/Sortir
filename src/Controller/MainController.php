<?php

namespace App\Controller;


use App\Entity\Category;
use App\Entity\School;
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

        $schoolRepository = $entityManager->getRepository(School::class);
        $school = $schoolRepository->findAll();


        $categoryRepository = $entityManager->getRepository(Category::class);
        $allCategory = $categoryRepository->findAll();


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


        $i=0;
        foreach ($allTrips as $trip){
            $coordinates=array((float)$trip->getLocation()->getLongitude(),(float)$trip->getLocation()->getLatitude());
            $city = $trip->getLocation()->getCity();
            $cityName = $city->getName();
            $data[$i]['type']='Feature';
            $data[$i]['geometry']['type']= 'Point';
            $data[$i]['geometry']['coordinates']=$coordinates;
            $data[$i]['properties']['title']=$trip->getName();
            $data[$i]['properties']['id']=$trip->getId();
            $data[$i]['properties']['title_location']=$trip->getLocation()->getName();
            $data[$i]['properties']['link']= $this->redirectToRoute('trip_detail',['id',$trip->getId()]);
            $data[$i]['properties']['address']=$trip->getLocation()->getStreet().' '.$cityName ;
            $i++;
        }
        $data = json_encode($data);
        return $this->render(
            'trip/list.html.twig',
            ['data'=>$data, 'allCategories'=>$allCategory, 'allSchools'=>$school, 'allTrips' => $allTrips, 'searchFormView' => $searchForm->createView()]
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