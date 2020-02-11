<?php

namespace App\Controller;

use App\Entity\Trip;
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

        $trip = $this->getUser();
        $tripForm = $this->createForm(TripType::class, $trip);

        $trip_location = $this->getUser();
        $trip_LocationForm = $this->createForm(TripLocationType::class, $trip_location);


        $tripForm->handleRequest($request);
        $trip_LocationForm->handleRequest($request);

        if ($tripForm->isSubmitted() && $tripForm->isValid() &&$trip_LocationForm->isSubmitted() && $trip_LocationForm->isValid()) {

            $entityManager->persist($trip);
            $entityManager->persist($trip_location);
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
                'tripFormView' => $tripForm->createView(),'trip_LocationFormView' => $trip_LocationForm->createView(),
            ]
        );

    }
}