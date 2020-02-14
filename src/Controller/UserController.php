<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\UserUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



/**
 * @Route("/user", name="user_")
 */

class UserController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
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

        return $this->render(
            'user/login.html.twig',
            [
                'last_username' => $lastUsername, // dernier username connecté
                'error' => $error, // les erreurs de connexion
            ]
        );


    }

    /**
     * @route("/profil", name="profil")
     */
    public function profil (Request $request, EntityManagerInterface $entityManager)
    {
        // récupérer le user qui est connecté

        $user = $this->getUser();

        // créer un formulaire Usertype et y ajouter le User
        $userProfil = $this->createForm(UserUpdateType::class,$user);
        $userProfil->handleRequest($request);

        // traitement après soumission du form
        if ($userProfil->isSubmitted() && $userProfil->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'profil modifié !');

            return $this->redirectToRoute('home');
        }


        return $this->render('user/profil.html.twig', ['userFormView' => $userProfil->createView()]);

    }


    /**
     * @route ("/password", name="password")
     */
    public function passwordChange (Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        //$em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $resetPassword = new ResetPassword();
        $mdpForm = $this->createForm(ChangePasswordType::class, $resetPassword);

        $mdpForm->handleRequest($request);
        if ($mdpForm->isSubmitted() && $mdpForm->isValid()){

                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $mdpForm->get('newPassword')->getData()
                    ));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success','Votre mot de passe a  bien été changé !');
            return $this->redirectToRoute('profil');

        }


        return $this->render('user/password.html.twig', ['mdpFormView' => $mdpForm->createView()]);
    }


    /**
     * @route("/detail/{id}", name="detail", requirements={"id": "\d+"})
     */

    public function detail($id, EntityManagerInterface $entityManager)

    {

        $userRepo = $entityManager->getRepository(User::class);
        $organizer = $userRepo->find($id);

        return $this->render('user/detail.html.twig', compact('organizer'));

    }




















































}
