<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/category', name: 'app_admin_category_')]
class CategoryController extends AbstractController
{

    public function __construct(private SluggerInterface $slugger){}

    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();
        return $this->render('admin/category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categories
        ]);
    }
    #[Route('/add', name:'add')]
    public function addCategory(Request $request, 
    EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // dd($form->getData()->getName());
            $category->setName($form->getData()->getName());
            $category->setSlug($this->slugger->slug($category->getName())->lower());
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('admin/category/add.html.twig',
        [
            'addCategoryForm' => $form->createView(),
        ]);
    
    }

    #[Route('/edit/{id}', name:'edit')]
    public function editCategory(Request $request, int $id,
    EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);
        if($category){
            $form = $this->createForm(CategoryType::class, $category);
            $form->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()){
                $category->setName($form->getData()->getName());
                $category->setSlug($this->slugger->slug($category->getName())->lower());
                $entityManager->persist($category);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_admin_category_index');
            }
        }else{
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('admin/category/edit.html.twig',
        [
            'editCategoryForm' => $form->createView(),
        ]);  
    }

    #[Route('delete/{id}', 'delete')]
    public function deleteCategory( EntityManagerInterface $entityManager, int $id ):Response
    {
        $category = $entityManager->getRepository(Category::class)->find($id);
        if($category){
            $entityManager->remove($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_category_index');
        }
    }
}
