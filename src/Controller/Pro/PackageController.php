<?php

namespace App\Controller\Pro;

use App\Entity\Gallery;
use App\Entity\Package;
use App\Form\PackageType;
use App\Form\EditPackageType;
use App\Repository\BookRepository;
use App\Repository\EstablishmentRepository;
use App\Repository\PackageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * 
 * @IsGranted("ROLE_PRO")
 * 
 */
class PackageController extends AbstractController
{
    /**
     * Add a new package in the DB
     * 
     * @Route("/business/packages", name="app_pro_new_package", methods={"GET", "POST"})
     */
    public function addPackage(Request $request, PackageRepository $packageRepository): Response
    {
        $package = new Package();
        
        /** @var \App\Entity\User $user */

        $form = $this->createForm(PackageType::class, $package,['user'=> $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // We create a variable $pictures which retrieve his datas gallery
            $pictures = $form->get('galleries')->getData();

            // We loop on pictures
            foreach($pictures as $picture){
                // We create a new file
                $file = md5(uniqid()).'.'.$picture->guessExtension();
                
                // We copy the file in the upload directory
                $picture->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                
                // We create the picture in the database
                $img = new Gallery();
                $img->setPicture($file);
                $package->addGallery($img);
            }

            $packageRepository->add($package, true);

            $this->addFlash('success', 'L\'ajout du package ' . $package->getName() . ' a bien été créé');

            return $this->redirectToRoute('app_pro_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Pro/package/new.html.twig', [
            'package' => $package,
            'form' => $form,
        ]);
    }

    /**
     * Update a package in the DB
     * 
     * @Route("/business/packages/{id}", name="app_pro_update_package", methods={"GET", "POST"})
     */
    public function updatePackage(Request $request, Package $package, PackageRepository $packageRepository): Response
    {
        
        $form = $this->createForm(EditPackageType::class, $package);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $packageRepository->add($package, true);

            $this->addFlash('success', 'La modification du package ' . $package->getName() . ' a bien été prise en compte');

            return $this->redirectToRoute('app_pro_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Pro/package/edit.html.twig', [
            'package' => $package,
            'form' => $form,
        ]);
    }

    /**
     * Delete a package in the BDD
     * 
     * @Route("/business/packages/{id}/delete", name="app_pro_delete_package", methods={"POST"})
     */
    public function deletePackage(Request $request, Package $package, PackageRepository $packageRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$package->getId(), $request->request->get('_token'))) {
            $packageRepository->remove($package, true);

            $this->addFlash('success', 'La suppression du package ' . $package->getName() . ' a bien été prise en compte');
        }
        return $this->redirectToRoute('app_pro_home', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
    * Browse all the reservation owned by the user
    *
    * @Route("/business/books", name="app_pro_reservations", methods={"GET"})
    */
    public function showBook ( EstablishmentRepository $establishmentRepository )
    {
        
         /** @var \App\Entity\User $user */
        $user = $this->getUser();
        
        // we retrieve his establishments
        $listEstablishment = $establishmentRepository->findByUser($user);
        
        return $this->render('Pro/reservations_list.html.twig', [
            'listEstablishment' => $listEstablishment,
        ]);
    }

    /**
    * confirmed the book
    * update the entity status in the DB
    * 
    * @Route("/business/books/validated/{id}", name="app_pro_update_book_validated", requirements={"id"="\d+"})
    */
    public function bookValidate ($id, BookRepository $bookRepository, ManagerRegistry $doctrine)
    {
        //we find the entity book in our DB
        $book = $bookRepository->find($id);
        
        //Set new value (1=book validate)
        $book->setStatus(1);
        //Set bool for user message
        $book->setMessageStatus(false);

        //Save in the DB
        $entityManager =$doctrine->getManager();
        $entityManager->flush();

        $this->addFlash('success', 'La réservation a bien été confirmée');

        return $this->redirectToRoute('app_pro_reservations', [], Response::HTTP_SEE_OTHER);
    }

    /**
    * 
    *Cancel the book
    *
    * @Route("/business/books/cancelled/{id}", name="app_pro_update_book_cancelled", requirements={"id"="\d+"})
    */
    public function bookCancel ($id, BookRepository $bookRepository, ManagerRegistry $doctrine)
    {
        //we find the entity book in our DB
        $book = $bookRepository->find($id);
        
        //Set new value (2=book cancelled)
        $book->setStatus(2);
        //Set bool for user message
        $book->setMessageStatus(false);

        $entityManager =$doctrine->getManager();
        $entityManager->flush();

        $this->addFlash('success', 'La réservation a été annulée');

        return $this->redirectToRoute('app_pro_reservations', [], Response::HTTP_SEE_OTHER);
    }
}
