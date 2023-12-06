<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $numberOfCategories = $categoryRepository->count([]);
        $categories = $categoryRepository->findAll();
        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'number_of_categories' => $numberOfCategories,
            'categories' => $categories,


        ]);
    }
}
