<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserAddType;
use Cassandra\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        return $this->render('admin/index.html.twig',compact('allUsers'));
    }

    /**
     * @Route("/user/{id}", name="user_detail", requirements={"id" : "\d+"})
     */
    public function detail(EntityManagerInterface $entityManager, $id)
    {
        $userRepository = $entityManager->getRepository(User::class);
        $currentUser    = $userRepository->find($id);
        return $this->render('admin/user/detail.html.twig', compact('currentUser'));
    }

    /**
     * @Route("/user/add/{id}", name="user_add", requirements={"id" : "\d+"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager, $id = 0, UserPasswordEncoderInterface $passwordEncoder)
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
    public function update(Request $request, EntityManagerInterface $entityManager, $id = 0)
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

            $this->addFlash('success', 'utilisateur modifié !');

            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/user/update.html.twig', ['userFormView' => $userForm->createView()]);
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete", requirements={"id" : "\d+"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, $id = 0){
        $userRepository = $entityManager->getRepository(User::class);
        $userToDelete = $userRepository->find($id);
        $entityManager->remove($userToDelete);
        $entityManager->flush();
        $this->addFlash('danger','Utilisateur supprimé !');

        return $this->redirectToRoute('admin_home');
    }

}
