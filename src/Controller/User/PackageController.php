<?php

namespace App\Controller\User;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\PackageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PackageController extends AbstractController
{   
    /**
     * Show one package by id
     *
     * @Route("/packages/{id}", name="app_user_package_show", requirements={"id"="\d+"}, methods={"GET", "POST"})
     *
     *  @param [type] $id
     */
    public function packageShow($id,Request $request, BookRepository $bookRepository, PackageRepository $packageRepository)
    {
        // show package by id
        $package = $packageRepository->find($id);

        // Package not found ?
        if ($package === null) {
            throw $this->createNotFoundException('Ce package n\'existe pas');
        }

        //Get user who is connected
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        //! Booking Form
        $book = new Book();
        
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $book->setStatus(0);
            $book->setUser($user);
            $book->setPackages($package);
            $book->setPrice($package->getPrice());
            // dd($book);
            $bookRepository->add($book, true);

            //Flash Message for the customer
            $this->addFlash('success', 'Votre réservation est en cours de confirmation.');

            return $this->redirectToRoute('app_user_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('User/packageShow.html.twig', [
            'package' => $package,
            'form' => $form,
            'book' => $book,
        ]);
    }
}