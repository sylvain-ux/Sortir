<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\CityType;
use App\Form\TripLocationType;
use App\Form\TripType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trip", name="trip_")
 */

class TripController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render(
            'trip/index.html.twig',
            [
                'controller_name' => 'TripController',
            ]
        );
    }


    /**
     * @Route("/create", name="create")
     */
    public function createtrip(Request $request, EntityManagerInterface $entityManager)
    {

                $user = $this->getUser();
                $tripForm = $this->createForm(TripType::class);

                $tripForm->handleRequest($request);


                if ($tripForm->isSubmitted() && $tripForm->isValid() ) {


                    $entityManager->persist($tripForm);

                    $entityManager->flush();
                    $this->addFlash(
                        'success',
                        'Sortie ajoutée !'
                    );

                    return $this->redirectToRoute("home");
                }


                return $this->render(
                    'trip/create.html.twig',
                    [
                        'tripFormView' => $tripForm->createView(),
                    ]
                );



/*
        $trip = $this->getUser();
        $tripForm = $this->createForm(TripType::class, $trip);

//        $trip_location = $this->getUser();
//        $trip_LocationForm = $this->createForm(TripLocationType::class, $trip_location);
//
//        $city = $this->getUser();
//        $cityForm = $this->createForm(CityType::class, $city);

        $tripForm->handleRequest($request);
//        $trip_LocationForm->handleRequest($request);
//        $cityForm->handleRequest($request);

        if ($tripForm->isSubmitted() && $tripForm->isValid() ) {

            $entityManager->persist($trip);
//            $entityManager->persist($trip_location);
//            $entityManager->persist($city);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Sortie ajoutée !'
            );

            return $this->redirectToRoute("home");
        }


        return $this->render(
            'trip/create.html.twig',
            [
                'tripFormView' => $tripForm->createView(),
//                'trip_LocationFormView' => $trip_LocationForm->createView(),
//                'cityFormView' => $cityForm->createView(),
            ]
        );*/

    }

}