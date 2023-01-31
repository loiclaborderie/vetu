<?php namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\data\Todo;



#[Route('/todo')]
class TodoController extends AbstractController
{
    private array $todolist;
    #[Route('/', name: 'app.todo', methods: "GET")]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
    
        if($session->get('todolist') == null){
            $session->set('todolist', $this->init());
        }
    
        $todolist = $session->get('todolist');
    
        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
            'todolist' => $todolist
        ]);
    }
    
    #[Route('detail/{id}', name: 'app.todo.detail', methods: 'GET')]
    public function detail(Request $request, int $id) : Response{
        $session = $request->getSession();
    
        $todolist = $session->get('todolist');
        $result = null;
        foreach($todolist as $todo){
            if($todo->id === $id){
                $result = $todo;
            }
        }
        if($result==null){
            $this->addFlash("warning", "La todo que vous cherchez n'existe pas");
        }
    
        // dd($result);
            return $this->render('todo/detail.html.twig',[
                'todo' => $result
            ]);
        }
    

        // definir route supprseion tache
        // definir button suppr
        // envoyer id a suppr dans la route
        // parcourir todolist et suppr le todo avec les id


        private function init():array{
            return [
                new Todo("Apprendre Symfony","blalabakjdkjkdjjjfjehfj"),
                new Todo("créer un controller","jlkjelkjflkrejlekrjglkrj"),
                new Todo("manipuler les données","lkjiorijeoiigferoigoirgjitjgoii")
            ];
        }

}






