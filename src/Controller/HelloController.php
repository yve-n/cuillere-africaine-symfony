<?php 
namespace App\Controller;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HelloController extends AbstractController{

    private array $messages = ["hello", "hi", "world"];

    #[Route('/hello',name:'app_index')]
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

    #[Route('/messages/add',name:'app_messages_add')]
    public function add():Response
    {
        $category = new Category();
        $form = $this->createFormBuilder($category)
        ->add('name')
        ->add('slug')
        ->add('submit',SubmitType::class, ['label'=>'save'])
        ->getForm();
        return $this->render('Hello/add.html.twig',
        [
            'form' => $form
        ]);
    }


}