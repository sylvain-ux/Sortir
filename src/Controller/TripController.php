<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\City;
use App\Entity\School;
use App\Entity\State;
use App\Entity\Trip;
use App\Entity\TripLocation;
use App\Entity\User;
use App\Form\CityType;
use App\Form\SearchType;
use App\Form\TripCancelType;
use App\Form\TripDetailType;
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
     * @Route("/map", name="map")
     */
    public function map(EntityManagerInterface $entityManager)
    {
        $schoolRepository = $entityManager->getRepository(School::class);
        $allSchools = $schoolRepository->findAll();
        $stateRepository = $entityManager->getRepository(State::class);
        $allStates = $stateRepository->findAll();
        $locationRepository = $entityManager->getRepository(TripLocation::class);
        $allLocations = $locationRepository->findAll();
        $tripRepository = $entityManager->getRepository(Trip::class);
        $allTrips = $tripRepository->findAll();
        //$data = array();
        $i=0;
        foreach ($allLocations as $location){
            $coordinates=array((float)$location->getLongitude(),(float)$location->getLatitude());
            $city = $location->getCity();
            $cityName = $city->getName();
            $data[$i]['type']='Feature';
            $data[$i]['geometry']['type']= 'Point';
            $data[$i]['geometry']['coordinates']=$coordinates;
            $data[$i]['properties']['title']=$location->getName();
            $data[$i]['properties']['address']=$location->getStreet().' '.$cityName ;
            $i++;
        }
        $data = json_encode($data);
        return $this->render('trip/map.html.twig',compact('allSchools','allStates','allLocations','allTrips','data'));
    }
    /**
     * @Route("/list", name="list")
     */
    public function list(EntityManagerInterface $entityManager)
    {
        $tripRepository = $entityManager->getRepository(School::class);
        $allSchools = $tripRepository->findAll();
        $tripRepository = $entityManager->getRepository(Trip::class);
        $allTrips = $tripRepository->findAll();
        $catRepository = $entityManager->getRepository(Category::class);
        $allCategories = $catRepository->findAll();
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
        return $this->render('trip/list.html.twig',compact('allTrips','allSchools','allCategories','data'));
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

            $this->addStateToTrip($entityManager, $trip->getId());

//            //Ajout de l'organisateur à la sortie nouvellement créée
//            $this->addUserToTrip($entityManager, $trip->getId());


            return $this->redirectToRoute("home");
        }


        return $this->render(
            'trip/create.html.twig',
            [
                'currentTrip'=>$trip, 'tripFormView' => $tripForm->createView(),
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
            //check if city in db
            $city = $trip_LocationForm->get('city')->getData();
            $zip_code = $trip_LocationForm->get('zip_code')->getData();
            $checkRepository = $entityManager->getRepository(City::class);
            $cityExists = $checkRepository->findOneByName($city);
            if(!$cityExists){
                $newCity = new City();
                $newCity->setName($city);
                $newCity->setZipCode($zip_code);
                //insert new city in db
                $entityManager->persist($newCity);
                $entityManager->flush();
                //$cityId = $newCity->setId();
                $location->setCity($newCity);
                //$locationForm = $this->createForm(LocationAddType::class, $location);
                //$this->addFlash('danger', 'Cette ville ne semble pas être dans la limite');
                //return $this->render('admin/location/add.html.twig', ['locationFormView' => $locationForm->createView()]);
            }else {
                $location->setCity($cityExists);
            }
            $entityManager->persist($location);
            $entityManager->flush();
            $locationId = $location->getId();
            $this->addFlash('success', 'Lieu ajouté !');

            return $this->redirectToRoute('trip_create');

        }


        return $this->render(
            'trip/createLocation.html.twig',
            [
                'trip_LocationFormView' => $trip_LocationForm->createView(),
            ]
        );

    }


    /**
     * Fonction permettant d'ajouter un utilisateur sur la sortie sélectionnée par l'utilisateur et de passer la sortie au status cloturée si le nombre d'inscrit maximum est atteint
     * @Route("/inscription/{id}", name="inscription", requirements={"id":"\d+"})
     */
    public function addUserToTrip(EntityManagerInterface $entityManager, $id = 0)
    {

        //je récupère le status dans la BDD correspond à l'ID souhaité avec un find by
        $stateRepository = $entityManager->getRepository(State::class);
        $stateClosed = $stateRepository->find('3');

        //Je récupère l'utilisateur courant
        $currentUser = $this->getUser();

        $tripRepository = $entityManager->getRepository(Trip::class);

        //Je récupère la sortie actuelle avec le paramètre de l'Id de la sortie récupérée sur la page de la liste des sorties
        $currentTrip = $tripRepository->find($id);


        //J'ajoute à la sortie actuelle l'utilisateur courant
        $currentTrip->addUser($currentUser);

        $nbInscrit = count($currentTrip->getUsers());
        $nbMaxInscrit = $currentTrip->getNbRegistMax();

        //Si le nb d'inscrit est egal au nb max d'inscrit Alors je fais un setState sur le trip current et ensuite persist et flush
        if ($nbInscrit == $nbMaxInscrit) {
            $currentTrip->setState($stateClosed);
        }

        //Récupération de tous les utilisateurs de la sortie
        $allUsers = $currentTrip->getUsers();


        //Je l'ajoute en BDD
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($currentTrip);
        $entityManager->flush();

        //Message de success
        $this->addFlash('success', 'Vous êtes inscrit sur la sortie');



        return $this->render(
            'trip/detail.html.twig',
            ['currentTrip' => $currentTrip, 'allUsers' => $allUsers]
        );
    }


    /**
     * Fonction permettant de supprimer un utilisateur qui veut se désister d'une sortie et de passer le status de la sortie de "Cloturée" à "Ouverte"
     * @Route("/unsubscribe/{id}", name="unsubscribe", requirements={"id":"\d+"})
     */
    public function remove(EntityManagerInterface $entityManager, $id = 0)
    {
        //je récupère le status dans la BDD correspond à l'ID souhaité avec un find by
        $stateRepository = $entityManager->getRepository(State::class);
        $stateOpen = $stateRepository->find('2');
        $stateClosed = $stateRepository->find('3');


        //Je récupère l'utilisateur courant
        $currentUser = $this->getUser();
        $tripRepository = $entityManager->getRepository(Trip::class);

        //Je récupère la sortie actuelle
        $currentTrip = $tripRepository->find($id);

        //je retire l'utilisateur courant de la sortie actuelle
        $currentTrip->removeUser($currentUser);

        //Je modifie le status de la sortie pour la passer à "Ouverte" si le status de la sortie est "cloturée"
        if ($stateClosed->getId() != '3') {
            $currentTrip->setState($stateOpen);
        }

        //Récupération de tous les utilisateurs de la sortie
        $allUsers = $currentTrip->getUsers();

        //MaJ BDD
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($currentTrip);
        $entityManager->flush();

        //Message de success
        $this->addFlash('success', 'Vous avez désinscrit sur la sortie');

        return $this->render(
            'trip/detail.html.twig',
            ['currentTrip' => $currentTrip, 'allUsers' => $allUsers]
        );

    }

    /**
     * fonction 1 : permettant d'ajouter un status par défaut sur la sortie nouvellement créée
     */
    private function addStateToTrip(EntityManagerInterface $entityManager, $id = 0)
    {

        //Je crée un status vide
        $state = new State();

        //je récupère le status dans la BDD correspond à l'ID souhaité avec un find by
        $stateRepository = $entityManager->getRepository(State::class);
        $stateInProgress = $stateRepository->find('1');


        $tripRepository = $entityManager->getRepository(Trip::class);

        //Je récupère la sortie actuelle
        $currentTrip = $tripRepository->find($id);

        //J'injecte le status par défaut dans la sortie actuelle
        $currentTrip->setState($stateInProgress);

        //MaJ BDD
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($currentTrip);
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
        $tripForm = $this->createForm(TripUserUpdateType::class, $trip);
        $tripForm->handleRequest($request);

        //Récupération de tous les participants de la sortie
        $allUsers = $trip->getUsers();

        if ($tripForm->isSubmitted() && $tripForm->isValid()) {

            if ($tripForm->getClickedButton() === $tripForm->get('drop')) {



                $entityManager = $this->getDoctrine()->getManager();
                //Suppression de la sortie associée à son id
                $entityManager->remove($trip);
                //Enregistrement des modifications dans la BDD
                $entityManager->flush();

                //Message de success
                $this->addFlash('success', 'Vous avez supprimé la sortie');

                return $this->redirectToRoute('home');
            }

            if ($tripForm->getClickedButton() === $tripForm->get('published')) {

                //je récupère le status dans la BDD correspond à l'ID souhaité avec un find by
                $stateRepository = $entityManager->getRepository(State::class);
                $stateInProgress = $stateRepository->find('2');

                //J'injecte le status par défaut dans la sortie actuelle
                $trip->setState($stateInProgress);

                //MaJ BDD
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($trip);
                $entityManager->flush();


                //Message de success
                $this->addFlash('success', 'Vous avez publié la sortie');

                return $this->render('trip/detail.html.twig', ['currentTrip'=>$trip, 'allUsers' => $allUsers]);
            }

            if ($tripForm->getClickedButton() === $tripForm->get('save')) {


                $entityManager->persist($trip);
                $entityManager->flush();

                $tripId = $trip->getId();

                $this->addFlash('success', 'Sortie modifiée !');

                return $this->render('trip/detail.html.twig', ['currentTrip'=>$trip, 'allUsers' => $allUsers]);
            }
        }

        return $this->render('trip/update.html.twig', ['trip'=>$trip, 'tripFormView' => $tripForm->createView()]);
    }

    /**
     * @Route("/detail/{id}", name="detail", requirements={"id" : "\d+"})
     */
    public function detailTrip(Request $request, EntityManagerInterface $entityManager, $id = 0)
    {
        $tripRepository = $entityManager->getRepository(Trip::class);
        if ($id) {
            $trip = $tripRepository->find($id);
        } else {
            $trip = new Trip();
        }
    //    $tripForm = $this->createForm(TripDetailType::class, $trip);

        $allUsers = $trip->getUsers();

            $coordinates=array((float)$trip->getLocation()->getLongitude(),(float)$trip->getLocation()->getLatitude());
            $city = $trip->getLocation()->getCity();
            $cityName = $city->getName();
            $data[0]['type']='Feature';
            $data[0]['geometry']['type']= 'Point';
            $data[0]['geometry']['coordinates']=$coordinates;
            $data[0]['properties']['title']=$trip->getLocation()->getName();
            $data[0]['properties']['address']=$trip->getLocation()->getStreet().' '.$cityName ;

        $data = json_encode($data);

        return $this->render(
            'trip/detail.html.twig',
            ['currentTrip' => $trip, 'allUsers' => $allUsers,'data' => $data]
        );
    }


    /**
     * @Route("/cancel/{id}", name="cancel", requirements={"id" : "\d+"})
     */
    public function cancelTrip(Request $request, EntityManagerInterface $entityManager, $id = 0)
    {
        $tripRepository = $entityManager->getRepository(Trip::class);
        if ($id) {
            $trip = $tripRepository->find($id);
        } else {
            $trip = new Trip();
        }
        $tripForm = $this->createForm(TripCancelType::class, $trip);

        $tripForm->handleRequest($request);

        if ($tripForm->isSubmitted() && $tripForm->isValid()) {

            //je récupère le status dans la BDD correspond à l'ID souhaité avec un find by
            $stateRepository = $entityManager->getRepository(State::class);
            $stateInProgress = $stateRepository->find('6');

            //J'injecte le status par défaut dans la sortie actuelle
            $trip->setState($stateInProgress);

            //MaJ BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trip);
            $entityManager->flush();

            //Message de success
            $this->addFlash('success', 'Vous avez annulé la sortie');

            return $this->redirectToRoute("home");
        }

        return $this->render('trip/cancel.html.twig', ['currentTrip'=>$trip, 'tripFormView' => $tripForm->createView()]);
    }


    /**
     * @Route("/summary:{id}", name="summary", requirements={"id":"\d+"})
     */
    public function summaryTrip(EntityManagerInterface $entityManager, $id = 0)
    {
        $tripRepository = $entityManager->getRepository(Trip::class);
        if ($id) {
            $trip = $tripRepository->find($id);
        } else {
            $trip = new Trip();
        }

        $allUsers = $trip->getUsers();

        return $this->render(
            'trip/recapitulatif.html.twig',
            ['currentTrip' => $trip, 'allUsers' => $allUsers]
        );
    }
}