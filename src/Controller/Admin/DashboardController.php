<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(CategoryRepository $categoryRepository,
    ProductRepository $productRepository): Response
    {
        $limit = 6;
        $numberOfCategories = $categoryRepository->count([]);
        $categories = $categoryRepository->findAll();
        $numberOfProducts = $productRepository->count([]);
        $lastProducts = $productRepository->getLastProducts($limit);
        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'number_of_categories' => $numberOfCategories,
            'categories' => $categories,
            'number_of_products' => $numberOfProducts,
            'last_products' => $lastProducts

        ]);
    }
}
