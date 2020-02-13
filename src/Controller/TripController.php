<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\State;
use App\Entity\Trip;
use App\Entity\TripLocation;
use App\Entity\User;
use App\Form\CityType;
use App\Form\TripLocationType;
use App\Form\TripType;
use App\Form\TripUpdateType;
use App\Form\TripUserUpdateType;
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


        $tripForm = $this->createForm(TripType::class, $trip);


        $tripForm->handleRequest($request);


        if ($tripForm->isSubmitted() && $tripForm->isValid()) {
            $trip->setSchool($school);

            $entityManager->persist($trip);

            $entityManager->flush();
            $this->addFlash(
                'success',
                'Sortie ajoutée !'
            );

            //Ajout d'un status par défaut pour la sortie sélectionée
   //         $this->addStateToTrip($entityManager, $trip->getId());

            //Ajout de l'organisateur à la sortie nouvellement créée
            $this->addUserToTrip($entityManager, $trip->getId());
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
     * Fonction permettant d'ajouter un utilisateur sur la sortie sélectionnée par l'utilisateur
     * @Route("/inscription/{id}", name="inscription", requirements={"id" : "\d+"})
     */
    public function addUserToTrip(EntityManagerInterface $entityManager,$id=0)
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
        $this->addFlash('success', 'Vous êtes inscrit sur la sortie');

        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/unsubscribe/{id}", name="unsubscribe", requirements={"id":"\d+"})
     */
    public function remove(EntityManagerInterface $entityManager, $id=0)
    {
        //Je récupère l'utilisateur courant
        $currentUser = $this->getUser();

        $tripRepository = $entityManager->getRepository(Trip::class);

        //Je récupère la sortie actuelle
        $currentTrip = $tripRepository->find($id);

        //je retire l'utilisateur courant de la sortie actuelle
        $currentTrip->removeUser($currentUser);

        //MaJ BDD
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($currentTrip);
        $entityManager->flush();

        //Message de success
        $this->addFlash('success', 'Vous avez désinscrit sur la sortie');

        return $this->redirectToRoute('home');

    }

    /**
     * fonction permettant d'ajouter un status par défaut sur la sortie nouvellement créée
     */
    private function addStateToTrip(EntityManagerInterface $entityManager, $id=0)
    {
        //Je crée un status vide
        $state = new State();



        $tripRepository = $entityManager->getRepository(Trip::class);

        //Je récupère la sortie actuelle
        $currentTrip = $tripRepository->find($id);

        //J'injecte le status par défaut dans la sortie actuelle
        $currentTrip->setStatus($state);


        //MaJ BDD
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($state);
        $entityManager->flush();
    }


    /**
     * @Route("/update/{id}", name="update", requirements={"id" : "\d+"})
     */
    public function updateTrip(Request $request, EntityManagerInterface $entityManager, $id = 0)
    {
        $tripRepository = $entityManager->getRepository(Trip::class);
        if ($id) {
            $trip = $tripRepository->find($id);
        } else {
            $trip = new Trip();
        }
        $tripForm = $this->createForm(TripUserUpdateType::class,$trip);
        $tripForm->handleRequest($request);

        if ($tripForm->isSubmitted() && $tripForm->isValid()) {

            $entityManager->persist($trip);
            $entityManager->flush();

            $tripId = $trip->getId();

            $this->addFlash('success', 'Sortie modifiée !');

            return $this->redirectToRoute('home');
        }
        return $this->render('trip/update.html.twig', ['tripFormView' => $tripForm->createView()]);
    }


    /**
     * Function whose change the status to close for a trip which is complete.
     * @Route("/statusclosed/{id}", name="statusclosed", requirements={"nbInscrit":"\d+", "nbMaxInscrit":"\d+", "id":"\d+"})
     */
    public function StatusClosed(EntityManagerInterface $entityManager, $nbInscrit=0, $nbMaxInscrit=0, $id=0)
    {
        //je récupère le status dans la BDD correspond à l'ID souhaité avec un find by
        $stateRepository = $entityManager->getRepository(State::class);
        $stateClosed = $stateRepository->find('3');

        $tripRepository = $entityManager->getRepository(Trip::class);
        $currentTrip = $tripRepository->find($id);


        //Si le nb d'inscrit est egal au nb max d'inscrit Alors je fais un setState sur le trip current et ensuite persist et flush
        if($nbInscrit == $nbMaxInscrit){
            $currentTrip->setState($stateClosed);
        }

        $entityManager->persist($currentTrip);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }
}