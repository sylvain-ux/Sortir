<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\SearchType;
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
    public function index(EntityManagerInterface $entityManager,Request $request)
    {
        $tripRepository = $entityManager->getRepository(Trip::class);
        $allTrips = $tripRepository->findAll();

        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);

        return $this->render('trip/index.html.twig',compact('allTrips'));

    }

    /**
     * @route("profil", name="profil")
     */
    public function profil(Request $request, EntityManagerInterface $entityManager)
    {
        // récupérer le user qui est connecté


        // créer un formulaire Usertype et y ajouter le User
        $userProfil = $this->createForm(UserUpdateType::class, $user);
        $userProfil->handleRequest($request);

        // traitement après soumission du form
        if ($userProfil->isSubmitted() && $userProfil->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'profil modifié !');

            return $this->redirectToRoute('home');
        }


        return $this->render('main/profil.html.twig', ['userFormView' => $userProfil->createView()]);


// envoyer le formulaire dans le template
// si le formulaire est soumis il persister et flusher


    }

    //
//    /**
//     * @Route("/search2", name="search2")
//     */
//    public function search(Request $request, EntityManagerInterface $entityManager)
//    {
//        $searchForm = $this->createForm(SearchType::class);
//        $searchForm->handleRequest($request);
//
//        return $this->render(
//            'trip/index.html.twig',
//            [
//                'searchFormView' => $searchForm->createView(),
//            ]
//        );
//    }


}