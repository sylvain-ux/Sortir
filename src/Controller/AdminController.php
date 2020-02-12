<?php

namespace App\Controller;

use App\Entity\School;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\SchoolAddType;
use App\Form\SchoolUpdateType;
use App\Form\UserAddType;
use Cassandra\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;

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
        return $this->render('admin/index.html.twig',compact('allUsers','allSchools'));
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

}
