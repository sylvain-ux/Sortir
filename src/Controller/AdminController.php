<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\School;
use App\Entity\State;
use App\Entity\Trip;
use App\Entity\TripLocation;
use App\Entity\User;
use App\Form\CityAddType;
use App\Form\LocationAddType;
use App\Form\LocationUpdateType;
use App\Form\RegistrationFormType;
use App\Form\SchoolAddType;
use App\Form\SchoolUpdateType;
use App\Form\StateAddType;
use App\Form\TripAddType;
use App\Form\TripUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $userRepository = $entityManager->getRepository(User::class);
        $allUsers = $userRepository->findAll();
        $schoolRepository = $entityManager->getRepository(School::class);
        $allSchools = $schoolRepository->findAll();
        $stateRepository = $entityManager->getRepository(State::class);
        $allStates = $stateRepository->findAll();
        $locationRepository = $entityManager->getRepository(TripLocation::class);
        $allLocations = $locationRepository->findAll();
        $cityRepository = $entityManager->getRepository(City::class);
        $allCities = $cityRepository->findAll();
        $tripRepository = $entityManager->getRepository(Trip::class);
        $allTrips = $tripRepository->findAll();
        return $this->render('admin/index.html.twig',compact('allUsers','allSchools','allStates','allLocations','allCities','allTrips'));
    }

    /**
     * @Route("/user/list", name="user_list")
     */
    public function listUser(EntityManagerInterface $entityManager)
    {
        $userRepository = $entityManager->getRepository(User::class);
        $allUsers = $userRepository->findAll();

        return $this->render('admin/user/list.html.twig',compact('allUsers'));
    }

    /**
     * @Route("/user/add/{id}", name="user_add", requirements={"id" : "\d+"})
     */
    public function addUser(Request $request, EntityManagerInterface $entityManager, $id = 0, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $userForm = $this->createForm(RegistrationFormType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $userForm->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $userId = $user->getId();

            //send email sendEmail(MailerInterface $mailer, $from, $to, $object, $body)
            $response = $this->forward('App\Controller\MailerController::sendEmail', [
                'from'  => 'info@sortir.com',
                'to' => $userForm->get('email')->getData(),
                'object' => 'compte créé',
                'body' => '
<p>votre compte vient d\'etre créé !</p>',
//<p>votre mot de passe est <strong>'.$userForm->get('plainPassword')->getData().'</strong></p>',
            ]);
            $this->addFlash('success', 'utilisateur ajouté !');

            return $this->redirectToRoute('admin_home');
        }


        return $this->render('admin/user/add.html.twig', ['userFormView' => $userForm->createView()]);

    }

    /**
     * @Route("/user/update/{id}", name="user_update", requirements={"id" : "\d+"})
     */
    public function updateUser(Request $request, EntityManagerInterface $entityManager, $id = 0,MailerInterface  $mailInterface)
    {
        $userRepository = $entityManager->getRepository(User::class);
        if ($id) {
            $user = $userRepository->find($id);
        } else {
            $user = new User();
        }
        $userForm = $this->createForm(\App\Form\UserType::class,$user);
        $userForm->handleRequest($request);


        if ($userForm->isSubmitted() && $userForm->isValid()) {


                $entityManager->persist($user);
                $entityManager->flush();


                $userId = $user->getId();

            //send email
            $mailInterface->send();

            $this->addFlash('success', 'utilisateur modifié !');

            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/user/update.html.twig', ['userFormView' => $userForm->createView()]);
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete", requirements={"id" : "\d+"})
     */
    public function deleteUser(Request $request, EntityManagerInterface $entityManager, $id = 0){
        $userRepository = $entityManager->getRepository(User::class);
        $userToDelete = $userRepository->find($id);
        $entityManager->remove($userToDelete);
        $entityManager->flush();
        $this->addFlash('danger','Utilisateur supprimé !');

        return $this->redirectToRoute('admin_home');
    }



    /**
     * @Route("/school/add/{id}", name="school_add", requirements={"id" : "\d+"})
     */
    public function addSchool(Request $request, EntityManagerInterface $entityManager, $id = 0)
    {
        $school = new School();
        $schoolForm = $this->createForm(SchoolAddType::class, $school);
        $schoolForm->handleRequest($request);
        if ($schoolForm->isSubmitted() && $schoolForm->isValid()) {
            $entityManager->persist($school);
            $entityManager->flush();
            $schoolId = $school->getId();
            $this->addFlash('success', 'ecole ajoutée !');
            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/school/add.html.twig', ['schoolFormView' => $schoolForm->createView()]);
    }

    /**
     * @Route("/school/update/{id}", name="school_update", requirements={"id" : "\d+"})
     */
    public function updateSchool(Request $request, EntityManagerInterface $entityManager, $id = 0)
    {
        $schoolRepository = $entityManager->getRepository(School::class);
        if ($id) {
            $school = $schoolRepository->find($id);
        } else {
            $school = new School();
        }
        $schoolForm = $this->createForm(SchoolUpdateType::class,$school);
        $schoolForm->handleRequest($request);

        if ($schoolForm->isSubmitted() && $schoolForm->isValid()) {

            $entityManager->persist($school);
            $entityManager->flush();

            $schoolId = $school->getId();

            $this->addFlash('success', 'école modifiée !');

            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/school/update.html.twig', ['schoolFormView' => $schoolForm->createView()]);
    }

    /**
     * @Route("/school/delete/{id}", name="school_delete", requirements={"id" : "\d+"})
     */
    public function deleteSchool(Request $request, EntityManagerInterface $entityManager, $id = 0){
        $schoolRepository = $entityManager->getRepository(School::class);
        $schoolToDelete = $schoolRepository->find($id);
        $entityManager->remove($schoolToDelete);
        $entityManager->flush();
        $this->addFlash('danger','école supprimée !');

        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/state/add/{id}", name="state_add", requirements={"id" : "\d+"})
     */
    public function addState(Request $request, EntityManagerInterface $entityManager, $id = 0)
    {
        $state = new State();
        $stateForm = $this->createForm(StateAddType::class, $state);
        $stateForm->handleRequest($request);
        if ($stateForm->isSubmitted() && $stateForm->isValid()) {
            $entityManager->persist($state);
            $entityManager->flush();
            $schoolId = $state->getId();
            $this->addFlash('success', 'statut ajouté !');
            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/state/add.html.twig', ['stateFormView' => $stateForm->createView()]);
    }

    /**
     * @Route("/location/list", name="location_list")
     */
    public function listLocation(EntityManagerInterface $entityManager)
    {
        $userRepository = $entityManager->getRepository(TripLocation::class);
        $allLocations = $userRepository->findAll();

        return $this->render('admin/location/list.html.twig',compact('allLocations'));
    }
    /**
     * @Route("/location/add/{id}", name="location_add", requirements={"id" : "\d+"})
     */
    public function addLocation(Request $request, EntityManagerInterface $entityManager, $id = 0)
    {
        $location = new TripLocation();
        $locationForm = $this->createForm(LocationAddType::class, $location);
        $locationForm->handleRequest($request);
        if ($locationForm->isSubmitted() && $locationForm->isValid()) {
            //check if city in db
            $city = $locationForm->get('city')->getData();
            $checkRepository = $entityManager->getRepository(City::class);
            $cityExists = $checkRepository->findOneByName($city);
            if(!$cityExists){
                $locationForm = $this->createForm(LocationAddType::class, $location);
                $this->addFlash('danger', 'Cette ville ne semble pas être dans la limite');
                return $this->render('admin/location/add.html.twig', ['locationFormView' => $locationForm->createView()]);
            }else {
                $location->setCity($cityExists);
                $entityManager->persist($location);
                $entityManager->flush();
                $locationId = $location->getId();
                $this->addFlash('success', 'Lieu ajouté !');

                return $this->redirectToRoute('admin_home');
            }
        }
        return $this->render('admin/location/add.html.twig', ['locationFormView' => $locationForm->createView()]);
    }
    /**
     * @Route("/location/update/{id}", name="location_update", requirements={"id" : "\d+"})
     */
    public function updateLocation(Request $request, EntityManagerInterface $entityManager, $id = 0)
    {
        $locationRepository = $entityManager->getRepository(TripLocation::class);
        if ($id) {
            $location = $locationRepository->find($id);
        } else {
            $location = new TripLocation();
        }
        $locationForm = $this->createForm(LocationUpdateType::class,$location);
        $locationForm->handleRequest($request);

        if ($locationForm->isSubmitted() && $locationForm->isValid()) {

            $entityManager->persist($location);
            $entityManager->flush();

            $locationId = $location->getId();

            $this->addFlash('success', 'Lieu modifié !');

            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/location/update.html.twig', ['locationFormView' => $locationForm->createView()]);
    }
    /**
     * @Route("/location/delete/{id}", name="location_delete", requirements={"id" : "\d+"})
     */
    public function deleteLocation(Request $request, EntityManagerInterface $entityManager, $id = 0){
        $locRepository = $entityManager->getRepository(TripLocation::class);
        $locToDelete = $locRepository->find($id);
        $entityManager->remove($locToDelete);
        $entityManager->flush();
        $this->addFlash('danger','Lieu supprimé !');

        return $this->redirectToRoute('admin_home');
    }

    /**
     * @Route("/city/add/{id}", name="city_add", requirements={"id" : "\d+"})
     */
    public function addCity(Request $request, EntityManagerInterface $entityManager, $id = 0)
    {
        $city = new City();
        $cityForm = $this->createForm(CityAddType::class, $city);
        $cityForm->handleRequest($request);
        if ($cityForm->isSubmitted() && $cityForm->isValid()) {
            $entityManager->persist($city);
            $entityManager->flush();
            $cityId = $city->getId();
            $this->addFlash('success', 'Ville ajoutée !');
            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/city/add.html.twig', ['cityFormView' => $cityForm->createView()]);
    }

    /**
     * @Route("/trip/list", name="trip_list")
     */
    public function listTrip(EntityManagerInterface $entityManager)
    {
        $userRepository = $entityManager->getRepository(Trip::class);
        $allTrips = $userRepository->findAll();

        return $this->render('admin/trip/list.html.twig',compact('allTrips'));
    }

    /**
     * @Route("/trip/add/{id}", name="trip_add", requirements={"id" : "\d+"})
     */
    public function addTrip(Request $request, EntityManagerInterface $entityManager, $id = 0)
    {
        $trip = new Trip();
        $tripForm = $this->createForm(TripAddType::class, $trip);
        $tripForm->handleRequest($request);
        if ($tripForm->isSubmitted() && $tripForm->isValid()) {
            $entityManager->persist($trip);
            $entityManager->flush();
            $cityId = $trip->getId();
            $this->addFlash('success', 'Sortie ajoutée !');
            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/trip/add.html.twig', ['tripFormView' => $tripForm->createView()]);
    }
    /**
     * @Route("/trip/update/{id}", name="trip_update", requirements={"id" : "\d+"})
     */
    public function updateTrip(Request $request, EntityManagerInterface $entityManager, $id = 0)
    {
        $tripRepository = $entityManager->getRepository(Trip::class);
        if ($id) {
            $trip = $tripRepository->find($id);
        } else {
            $trip = new Trip();
        }
        $tripForm = $this->createForm(TripUpdateType::class,$trip);
        $tripForm->handleRequest($request);

        if ($tripForm->isSubmitted() && $tripForm->isValid()) {

            $entityManager->persist($trip);
            $entityManager->flush();

            $tripId = $trip->getId();

            $this->addFlash('success', 'Sortie modifiée !');

            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/trip/update.html.twig', ['tripFormView' => $tripForm->createView()]);
    }

    /**
     * @Route("/city/auto/{id}", name="city_auto", requirements={"id" : "\d+"})
     */
/*    public function updateCitiesByDept(Request $request, EntityManagerInterface $entityManager, $id = 0)
    {
        $dept = $id;
        dump($dept);

        $url = 'https://geo.api.gouv.fr/departements/44/communes?fields=nom,codesPostaux&format=json&geometry=centre';


        $client = HttpClient::create();
        $response = $client->request('GET', $url,[
            'proxy' => '10.0.0.248:8080'
        ]);

        $statusCode = $response->getStatusCode();
// $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
// $contentType = 'application/json'
        $content = $response->getContent();
// $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
// $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        //return $this->render('admin/city/add.html.twig', ['cityFormView' => $cityForm->createView()]);
    }*/

}
