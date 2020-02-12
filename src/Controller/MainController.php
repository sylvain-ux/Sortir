<?php

namespace App\Controller;

use App\Entity\Trip;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class MainController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $tripRepository = $entityManager->getRepository(Trip::class);
        $allTrips = $tripRepository->findAll();

        $userTripRepository = $entityManager->getRepository(Trip::class);
        $allTrips = $userTripRepository->findAll();

        return $this->render('trip/index.html.twig', compact('allTrips'));

    }


    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError(); // Est-ce qu'il y a eu des erreurs ?

        // last name entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'main/login.html.twig',
            [
                'last_username' => $lastUsername, // dernier username connecté
                'error' => $error, // les erreurs de connexion
            ]
        );

    }


}