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
     * @route("profil/{id}, name="profil")
     */
    public function profil (Request $request, EntityManagerInterface $entityManager, $id=0)
    {
      // récupérer le user qui est connecté

        $user = $this->getUser();

//        $userRepository = $entityManager->getRepository(User::class);
//        if ($id) {
//            $user = $userRepository->find($id);
//        } else {
//            $user = new User();
//        }

        // créer un formulaire Usertype et y ajouter le User
        $userProfil = $this->createForm(UserType::class,$user);
        $userProfil->handleRequest($request);

        if ($userProfil->isSubmitted() && $userProfil->isValid()) {

            $userProfil->persist($user);
            $userProfil->flush();

//            $userId = $user->getId();
//
//            $this->addFlash('success', 'utilisateur ajouté !');

//            return $this->redirectToRoute('admin_home');
        }


        return $this->render('main/profil.html.twig', ['userFormView' => $userProfil->createView()]);


// envoyer le formulaire dans le template
// si le formulaire est soumis il persister et flusher

//        return $this->render('main/profil.html.twig', compact('user'));
    }














}