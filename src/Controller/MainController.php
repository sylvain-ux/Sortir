<?php

namespace App\Controller;

use App\Entity\User;
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
    public function index()
    {
        return $this->render('main/home.html.twig', [
            'controller_name' => 'MainController',
        ]);
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

        return $this->render('main/login.html.twig', [
            'last_username' => $lastUsername, // dernier username connecté
            'error' => $error, // les erreurs de connexion
        ]);

    }



    /**
     * @route("/profil", name="profil")
     */
    public function profil (EntityManagerInterface $entityManager)
    {
      // récupérer le user qui est conenecté

        $user = $this->getUser();

        // créer un formulaire User


        // y ajouter le $user ds le formulaire



        // envoyer le formulaire dans le template


        // si le formulaire est soumis il persister et flusher

        return $this->render('main/profil.html.twig', compact('user'));
    }














}