<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;

use App\Entity\Trip;
use App\Form\UserUpdateType;
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








}