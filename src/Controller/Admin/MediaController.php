<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Form\MediaSearchType;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/media')]
class MediaController extends AbstractController
{
    public function __construct(
        private MediaRepository $mediaRepository,
        private PaginatorInterface $paginator
    )
    {

    }
    #[Route('/', name: 'app_media')]
    public function index(Request $request): Response
    {

        $qb = $this->mediaRepository->getQbAll();
        
        $form = $this->createForm(MediaSearchType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            if($data['mediaTitle'] !== null){
                $qb->andWhere('m.title LIKE :title')
                ->setParameter('title', "%" . $data['mediaTitle'] . "%");
            }
            
            if($data['userEmail'] !== null){
                $qb->innerJoin('m.user', 'u')
                    ->andWhere('u.email = :email')
                ->setParameter('email', $data['userEmail']);
            }
            
            if($data['mediaCreated'] !== null){
                $qb->andWhere('m.createdAt > :createdAt')
                ->setParameter('createdAt', $data['mediaCreated']);
            }
        }

        //traitement de formulaire
        // si jamais on qqch
            //  ->updatenotre query
        
            $pagination = $this->paginator->paginate(
            $qb, $request->query->getInt('page', 1), 15
        );

        // $mediaEntity = $this->mediaRepository->findAll();
        return $this->render('media/index.html.twig', [
            'medias' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/show/{id}', name: 'app_media_show')]
    public function detail($id): Response
    {

        $mediaEntity = $this->mediaRepository->find($id);

        if ($mediaEntity === null){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('media/show.html.twig', [
            'media' => $mediaEntity
        ]);
    }

    #[Route('/add', name: 'app_media_add')]
    public function add(Request $request, SluggerInterface $slugger): Response
    {

        /**
         * récupere l'utilisateur connecté
         * soit une entité User (si connecté)
         * soit null si (pas connecté)
         */
        $user = $this->getUser();


        // if($user === null){
        //     return $this->redirectToRoute('app_home');
        // }

        $mediaEntity = new Media();
        $mediaEntity->setUser($user);
        $mediaEntity->setCreatedAt(new \DateTime());

        $form = $this->createForm(MediaType::class, $mediaEntity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $mediaEntity->setSlug($slug = $slugger->slug($mediaEntity->getTitle()));
        }

        return $this->render('media/add.html.twig', [
            'formMedia' => $form->createView()
        ]);
    }

}
