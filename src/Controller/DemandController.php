<?php

namespace App\Controller;

use App\Entity\Demand;
use App\Form\DemandType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DemandController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {

    }
    #[Route('/demmand', name: 'app_demand')]
    public function index(Request $request): Response
    {
        $demandEntity = new Demand();
        $form = $this->createForm(DemandType::class, $demandEntity);
        $form->handleRequest($request);

        $demandEntity->setStatus('non_lu');
        $demandEntity->setCreatedAt(new \DateTime());

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($demandEntity);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('demand/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
