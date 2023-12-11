<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/product', name: 'app_admin_product_')]
class ProductController extends AbstractController
{

    public function __construct(private SluggerInterface $slugger)
    {
        
    }
    #[Route('/', name: 'index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('admin/product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products
        ]);
    }

    #[Route('/add', name:'add')]
    public function addProduct(EntityManagerInterface $entityManager,
    Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product->setSlug($this->slugger->slug($product->getName())->lower());
            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product Added successfully');

            return $this->redirectToRoute('app_admin_product_index');
        }

        return $this->render('admin/product/add.html.twig', [
            'addProductForm' => $form->createView(),
        ]);
        
    }

    #[Route('/edit/{id}', name:'edit')]
    public function editProduct(EntityManagerInterface $entityManager,
    Request $request, int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if ($product) {
            $form = $this->createForm(ProductType::class, $product);
            $form->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()){
                $product->setSlug($this->slugger->slug($product->getName())->lower());
                $entityManager->persist($product);
                $entityManager->flush();
                
                $this->addFlash('success', 'Product updated successfully');
                return $this->redirectToRoute('app_admin_product_index');
            }
           
        }else{
            return $this->redirectToRoute('app_admin_dashboard');
        }

        return $this->render('admin/product/edit.html.twig', [
            'editProductForm' => $form->createView(),
        ]);
        
    }

    #[Route('/delete/{id}', name:'delete')]
    public function deleteProduct(int $id, EntityManagerInterface $entityManager)
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        if($product){
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash('success', 'Product deleted successfully');
            return $this->redirectToRoute('app_admin_product_index');
        }
    }
}
