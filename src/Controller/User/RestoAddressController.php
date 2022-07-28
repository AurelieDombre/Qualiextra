<?php

namespace App\Controller\User;

use App\Entity\Establishment;
use App\Entity\Tag;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\EstablishmentRepository;
use App\Repository\StyleRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestoAddressController extends AbstractController
{
    /**
     * @Route("/good-places", name="app_user_resto_address")
     */
    public function listResto(EstablishmentRepository $establishmentRepository, StyleRepository $styleRepository, TagRepository $tagRepository): Response
    {

        $listEstablishment = $establishmentRepository->findAll();
        $listTag = $tagRepository->findAll();
        $listStyle = $styleRepository->findAll();
      
          
        
        return $this->render('User/goodPlace.html.twig', [
            'listEstablishment' => $listEstablishment,
            'listTag' => $listTag,
            'listStyle' => $listStyle,
        ]);
    }
}
