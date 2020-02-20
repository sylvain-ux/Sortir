<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\Trip;
use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\UploadAvatarType;
use App\Form\UserUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
        return $this->render(
            'user/index.html.twig',
            [
                'controller_name' => 'UserController',
            ]
        );
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
     * @route("/escale", name="escale")
     *
     */
    public function escale(Request $request, EntityManagerInterface $entityManager)
    {

        $user = $this->getUser();
        //dump($user->getRoles());
        //die();
        if ($user->getRoles() == ['ROLE_BANISHED']) {

            $this->addFlash('danger', "Votre compte a été désactivé. Veuillez contacter un administrateur.");

            return $this->redirectToRoute('user_login');

        } elseif (in_array('ROLE_ADMIN', $user->getRoles())) {

            return $this->redirectToRoute('admin_home');
        } else {
            return $this->redirectToRoute('home');
        }


        //($user->getRoles()==['ROLE_ADMIN'])


    }


    /**
     * @route("/profil", name="profil")
     */
    public
    function profil(
        Request $request,
        EntityManagerInterface $entityManager
    ) {
        // récupérer le user qui est connecté

        $user = $this->getUser();

        // créer un formulaire Usertype et y ajouter le User
        $userProfil = $this->createForm(UserUpdateType::class, $user);
        $userProfil->handleRequest($request);

        $userAvatar = $this->createForm(UploadAvatarType::class, $user);
        $userAvatar->handleRequest($request);

        // traitement du userProfil après soumission du form
        if ($userProfil->isSubmitted() && $userProfil->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'profil modifié !');

            return $this->redirectToRoute('user_profil');
        }

        // Traitement du user avatar
        if ($userAvatar->isSubmitted() && $userAvatar->isValid()) // je récupère la valeur du champs avatar
        {
            $avatarField = $userAvatar->get('avatarField')->getData();
            // $avatarField = $user -> getAvatarField();

            if ($avatarField) {
                $originalFilename = pathinfo($avatarField->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate(
                    'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                    $originalFilename
                );
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarField->guessExtension();
                try {
                    $avatarField->move(
                        sprintf($this->getParameter('avatar_directory'), $user->getId()),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $user->setAvatarName($newFilename);
                $user->setAvatarField(null);

            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez bien téléchargé votre avatar !');

            return $this->redirectToRoute('user_profil');


        }

        return $this->render(
            'user/profil.html.twig',
            ['userFormView' => $userProfil->createView(), 'uploadAvatarView' => $userAvatar->createView()]
        );

    }


    /**
     * @route ("/password", name="password")
     */
    public
    function passwordChange(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        //$em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $resetPassword = new ResetPassword();
        $mdpForm = $this->createForm(ChangePasswordType::class, $resetPassword);

        $mdpForm->handleRequest($request);
        if ($mdpForm->isSubmitted() && $mdpForm->isValid()) {

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $mdpForm->get('newPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a  bien été changé !');

            return $this->redirectToRoute('user_profil');

        }


        return $this->render('user/password.html.twig', ['user' => $user, 'mdpFormView' => $mdpForm->createView()]);
    }


    /**
     * @route("/detail/{id}/{idtrip}", name="detail", requirements={"id": "\d+","idtrip": "\d+"})
     */

    public
    function detail(
        $id, $idtrip,
        EntityManagerInterface $entityManager
    ) {

        $userRepo = $entityManager->getRepository(User::class);
        $organizer = $userRepo->find($id);


        $tripRepository = $entityManager->getRepository(Trip::class);
        if ($idtrip) {
            $trip = $tripRepository->find($idtrip);
        } else {
            $trip = new Trip();
        }


        return $this->render('user/detail.html.twig', ['organizer'=>$organizer, 'currentTrip'=>$trip]);

    }


}
