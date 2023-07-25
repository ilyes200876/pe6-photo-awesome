<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category')]
class CategoryController extends AbstractController
{

    public function __construct(
        private CategoryRepository $categoryRepository,
        private EntityManagerInterface $entityManager
    )
    {

    }

    #[Route('/', name: 'app_category')]
    public function index(): Response
    {
        $categoryEntities = $this->categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categoryEntities
        ]);
    }

    #[Route('/show/{id}', name: 'app_category_show')]
    public function detail($id): Response
    {

        $categoryEntity = $this->categoryRepository->find($id);

        if ($categoryEntity === null){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('category/show.html.twig', [
            'category' => $categoryEntity
        ]);
    }

    #[Route('/new', name: 'app_category_new')]
    public function add(Request $request): Response
    {
        
        $category =new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_category');

        }

        return $this->render('category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'app_category_delete')]
    public function delete($id): Response
    { 
        $categoryEntity = $this->categoryRepository->find($id);

        if ($categoryEntity !== null){
            $this->entityManager->remove($categoryEntity);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('app_category');
        
        // return $this->render('category/delete.html.twig', [
        //     'form' => $form->createView()
        // ]);
    }

    #[Route('/edit/{id}', name: 'app_category_edit')]
    public function edit($id, Request $request): Response
    { 
        $category = $this->categoryRepository->find($id);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_category');
        }


        return $this->render('category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
