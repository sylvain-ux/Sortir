<?php

namespace App\Controller;

use App\Entity\Trip;
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
     * @Route("/trip2", name="trip2_")
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
     * @Route("/createtrip", name="createtrip_")
     */
    public function createtrip(Request $request, EntityManagerInterface $entityManager)
    {

        $trip = $this->getUser();
        $tripForm = $this->createForm(TripType::class, $trip);

        return $this->render(
            'trip/create.html.twig',
            [
                'tripFormView' => $tripForm->createView(),
            ]
        );

    }
}