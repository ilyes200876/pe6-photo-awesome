<?php

namespace App\Controller\Admin;

use App\Repository\DemandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/demand')]
class DemandAdminController extends AbstractController
{
    public function __construct(
        private DemandRepository $demandRepository,
        private PaginatorInterface $paginator,
        private EntityManagerInterface $entityManager
    )
    {

    }
    
    #[Route('/', name: 'app_demand_admin')]
    public function index(Request $request): Response
    {

        $qb = $this->demandRepository->findQbAll();

        $pagination = $this->paginator->paginate(
            $qb, $request->query->getInt('page', 1), 5
        );
        
        return $this->render('demand_admin/index.html.twig', [
            'demands' => $pagination,
        ]);
    }

    #[Route('/LU/{id}', name: 'app_demand_admin_lu')]
    public function lu($id): Response
    {

        $demandLu = $this->demandRepository->find($id);
        $demandLu->setStatus('lu');

        if($demandLu !== null){
            
            
            $this->entityManager->persist($demandLu);
            $this->entityManager->flush();
        }
        
        return $this->render('demand_admin/lu.html.twig', [
            'demand' => $demandLu,
        ]);
        



    }
}
