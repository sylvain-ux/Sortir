<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Trip;
use App\Entity\TripLocation;
use App\Entity\User;
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
        $trip = new Trip();
        $trip->setUser($user);
        $school = $user->getSchool();
//        $city = new City();
//        $location = new TripLocation();
        //$location->setCity($city);

        $tripForm = $this->createForm(TripType::class, $trip);

//        $trip_location = $this->getUser();
//        $trip_LocationForm = $this->createForm(TripLocationType::class, $location);
//
//        $city = $this->getUser();
//        $cityForm = $this->createForm(CityType::class, $city);

        $tripForm->handleRequest($request);
//        $trip_LocationForm->handleRequest($request);
//        $cityForm->handleRequest($request);

        if ($tripForm->isSubmitted() && $tripForm->isValid()) {
            $trip->setSchool($school);

//            $trip->setLocation($location);
//            dump($trip);
//            die();
            $entityManager->persist($trip);
            //$entityManager->persist($trip_location);
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
        );

    }


    /**
     * @Route("/createLocation", name="createLocation")
     */
    public function createLocation(Request $request, EntityManagerInterface $entityManager)
    {


        $location = new TripLocation();

        $trip_LocationForm = $this->createForm(TripLocationType::class, $location);

        $trip_LocationForm->handleRequest($request);


        if ($trip_LocationForm->isSubmitted() && $trip_LocationForm->isValid()) {


            $entityManager->persist($location);

            $entityManager->flush();
            $this->addFlash(
                'success',
                'Lieu ajoutée !'
            );

            return $this->redirectToRoute("trip_create");
        }


        return $this->render(
            'trip/createLocation.html.twig',
            [
                'trip_LocationFormView' => $trip_LocationForm->createView(),
            ]
        );

    }


    /**
     * @Route("/inscription/{id}", name="inscription", requirements={"id" : "\d+"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager,$id=0)
    {
        //Je récupère l'utilisateur courant
        $currentUser = $this->getUser();

        $tripRepository = $entityManager->getRepository(Trip::class);

        //Je récupère la sortie actuelle avec le paramètre de l'Id de la sortie récupérée sur la page de la liste des sorties
        $currentTrip = $tripRepository->find($id);

        //J'ajoute à la sortie actuelle l'utilisateur courant
        $currentTrip->addUser($currentUser);

        //Je l'ajoute en BDD
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($currentTrip);
        $entityManager->flush();


        //Message de success
        $this->addFlash('success', 'utilisateur inscrit !');

        return $this->redirectToRoute('home');
    }


}