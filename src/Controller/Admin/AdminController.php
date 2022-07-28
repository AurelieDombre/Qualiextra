<?php

namespace App\Controller\Admin;

use App\Entity\Establishment;
use App\Entity\User;
use App\Form\EstablishmentType;
use App\Form\RegisterType;
use App\Repository\BookRepository;
use App\Repository\EstablishmentRepository;
use App\Repository\UserRepository;
use App\Services\Geocodage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * 
 * @IsGranted("ROLE_ADMIN")
 * 
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /** 
     * @Route("/", name="app_admin_home")
     */
    public function home(EstablishmentRepository $establishmentRepository, UserRepository $userRepository, BookRepository $bookRepository): Response
    {
        $establishmentsList = $establishmentRepository->findBy([], ['name' => 'ASC']);

        $usersList = $userRepository->findByRoles('USER');
        $prosList = $userRepository->findByRoles('PRO');

        $booksList = $bookRepository->findBy([], ['status' => 'ASC', 'date' => 'DESC']);

        return $this->render('admin/home.html.twig', [
            'establishmentsList' => $establishmentsList,
            'prosList' => $prosList,
            'usersList' => $usersList,
            'booksList' => $booksList,
        ]);
    }

    /**
     * @Route("/good-places", name="app_admin_establishment_new", methods={"GET", "POST"})
     */
    public function addResto(Request $request, EstablishmentRepository $establishmentRepository, Geocodage $geocodage): Response
    {
        $establishment = new Establishment();
        $form = $this->createForm(EstablishmentType::class, $establishment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // the address is geocoded 
            $address = $establishment->getAddress();
            $coordinates = $geocodage->geocoding($address);
            $lat = $coordinates['lat'];
            $long = $coordinates['lng'];
            $establishment->setLatitudes($lat);
            $establishment->setLongitudes($long);

            $establishmentRepository->add($establishment, true);

            $this->addFlash('success', 'L\'établissement ' . $establishment->getName() . ' à bien été créé');

            return $this->redirectToRoute('app_admin_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/establishment/addResto.html.twig', [
            'establishment' => $establishment,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/good-places/{id}", name="app_admin_establishment_edit", methods={"GET", "POST"}, requirements={"id"="\d+"})
     */
    public function updateResto(Request $request, Establishment $establishment, EstablishmentRepository $establishmentRepository,  Geocodage $geocodage): Response
    {
        $form = $this->createForm(EstablishmentType::class, $establishment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // the address is geocoded 
            $address = $establishment->getAddress();
            $coordinates = $geocodage->geocoding($address);
            $lat = $coordinates['lat'];
            $long = $coordinates['lng'];
            $establishment->setLatitudes($lat);
            $establishment->setLongitudes($long);

            $establishmentRepository->add($establishment, true);

            $this->addFlash('success', 'L\'établissement ' . $establishment->getName() . ' à bien été modifié');

            return $this->redirectToRoute('app_admin_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/establishment/updateResto.html.twig', [
            'establishment' => $establishment,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/good-places/delete/{id}", name="app_admin_establishment_delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function deleteResto(Request $request, Establishment $establishment, EstablishmentRepository $establishmentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $establishment->getId(), $request->request->get('_token'))) {
            $establishmentRepository->remove($establishment, true);

            $this->addFlash('success', 'L\'établissement ' . $establishment->getName() . ' à bien été supprimé');
        }

        return $this->redirectToRoute('app_admin_home', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/professionnels", name="app_admin_pro_new", methods={"GET", "POST"})
     */
    public function addPro(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_PRO']);
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);


            $userRepository->add($user, true);

            $this->addFlash('success', 'Le prestataire ' . $user->getEmail() . ' à bien été créé');

            return $this->redirectToRoute('app_admin_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/addPro.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/users/delete/{id}", name="app_admin_user_delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function deleteUser(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);

            $this->addFlash('success', 'L\'utilisateur ' . $user->getEmail() . ' à bien été supprimé');
        }

        return $this->redirectToRoute('app_admin_home', [], Response::HTTP_SEE_OTHER);
    }
}
