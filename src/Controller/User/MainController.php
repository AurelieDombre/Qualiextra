<?php

namespace App\Controller\User;

use App\Repository\BookRepository;
use App\Repository\PackageRepository;
use App\Repository\TypeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * Show package list with filter by type
     * 
     * @Route("/", name="app_user_home", methods={"GET"})
     */
    public function home(TypeRepository $typeRepository, BookRepository $bookRepository, PackageRepository $packageRepository): Response
    {
        $typeList = $typeRepository->findAll();

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
      
        if ($user !== null) {
            // Get user booking with user Id
            $books = $bookRepository->findByUser($user);
            // For display the flash message to each package which are booked
            foreach ($books as $book) {
                // we get the package associated to the reservation
                $package = $packageRepository->findOneById($book->getId());
                //If the message status = 0 so we display the response from the pro
                if ($book->isMessageStatus() === false) {
                    //If the pro valid the book => the book is confirmed
                    if ($book->getStatus() === 1) {
                        
                       
                        $this->addFlash('success '.$book->getId(), 'Votre réservation pour le package "' . $package->getName() . '" a bien été confirmée');
                    } //If the pro reject the book => the book is cancelled
                    else if ($book->getStatus() === 2) {
                        $this->addFlash('danger '.$book->getId(), 'Votre reservation pour le package "' . $package->getName() . '" a été annulée ');
                    }
                }
            }
        }

        return $this->render(
            'User/home.html.twig',
            [
                'typeList' => $typeList
            ]
        );
    }

    /**
     * Show CGV
     *
     * @Route("/CGV", name="app_user_cgv", methods={"GET"})
     */
    public function cgv()
    {
        return $this->render('User/cgv.html.twig');
    }


    /**
     * Allows to delete the messages related to the reservation when we click on the cross
     * 
     * @Route("/flash/{id}", name="app_user_flash")
     */
    public function flashBook ($id, BookRepository $bookRepository, ManagerRegistry $doctrine)
    {
          
        //we find the entity book in our BDD
        $book = $bookRepository->find($id);
        
        //Change bool for user message
        $book->setMessageStatus(true);

        //Save in the BDD
        $entityManager =$doctrine->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('app_user_home', [], Response::HTTP_SEE_OTHER);
    }
}
