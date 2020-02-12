<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\School;
use App\Entity\State;
use App\Entity\TripLocation;
use App\Entity\User;
use App\Form\CityAddType;
use App\Form\LocationAddType;
use App\Form\RegistrationFormType;
use App\Form\SchoolAddType;
use App\Form\SchoolUpdateType;
use App\Form\StateAddType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        return $this->render('admin/index.html.twig',compact('allUsers','allSchools','allStates','allLocations','allCities'));
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
     * @Route("/location/add/{id}", name="location_add", requirements={"id" : "\d+"})
     */
    public function addLocation(Request $request, EntityManagerInterface $entityManager, $id = 0)
    {
        $location = new TripLocation();
        $locationForm = $this->createForm(LocationAddType::class, $location);
        $locationForm->handleRequest($request);
        if ($locationForm->isSubmitted() && $locationForm->isValid()) {
            $entityManager->persist($location);
            $entityManager->flush();
            $locationId = $location->getId();
            $this->addFlash('success', 'Lieu ajouté !');
            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/location/add.html.twig', ['locationFormView' => $locationForm->createView()]);
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
}
