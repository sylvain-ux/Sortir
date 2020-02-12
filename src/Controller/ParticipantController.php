<?php
//
//namespace App\Controller;
//
//use App\Entity\User;
//use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\Routing\Annotation\Route;
//
//class ParticipantController extends AbstractController
//{
//    /**
//     * @Route("/participant", name="participant")
//     */
//    public function index()
//    {
//        return $this->render('participant/index.html.twig', [
//            'controller_name' => 'ParticipantController',
//        ]);
//    }
//
////@Route("/detail/{id}", name"detail", requirements={"id": "\d+"})
//
// /**
//  * @Route("/detail", name"detail")
//  */
// public function detail (EntityManagerInterface $entityManager){
//
//
//     $userRepository = $entityManager->getRepository(User::class);
//     $participant = $userRepository->find(id);
//
//
//     return $this->render('participant/detail.html.twig', compact('participant'));
//
//
//
// }
//
//
//
//
//
//
//
//
//}
