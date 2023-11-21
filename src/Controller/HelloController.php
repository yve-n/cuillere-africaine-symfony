<?php 
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController{

    private array $messages = ["hello", "hi", "world"];

    #[Route('/',name:'app_index')]
    public function index(): Response
    {
        return new Response(implode(', ', $this->messages));
    }

    #[Route('/messages/{id<\d+>}',name:'app_messages')]
    public function showOne($id): Response
    {
        return $this->render(
            'Hello/show_one.html.twig',
            ['message' => $this->messages[$id]]
        );
        
    }


}