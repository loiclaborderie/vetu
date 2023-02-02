<?php

namespace App\Controller;

use App\Entity\Film;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/film')]
class FilmController extends AbstractController
{
    // méthode affichant tout les films
    // utilise un param converter (FilmRepository $filmRepository)
    // ce qui nous evite de devoir aller chercher manuellement les film en bdd
    #[Route('/', name: 'app_film_index', methods: ['GET'])]
    public function index(FilmRepository $filmRepository): Response
    {
        return $this->render('film/index.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }

    // route permettant l'ajout des films
    #[Route('/new', name: 'app_film_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FilmRepository $filmRepository): Response
    {
        // on instancie un objet de type film
        $film = new Film();
        // on crée le formulaire en allant chercher le builder dans le dossier form et en l'associant à l'objet precedent
        $form = $this->createForm(FilmType::class, $film);
        // on récupère le formulaire
        $form->handleRequest($request);

        // dans le cas ou le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // dans le cas ou il n'y a pas d'exception levées
            try {
                // on va chercher la methode save du repository film pour enregistrement en base de données
                $filmRepository->save($film, true);
                // redirection vers la page affichage des films
                return $this->redirectToRoute('app_film_index', [], Response::HTTP_SEE_OTHER);
                // dans le cas ou une exception est levée
            } catch (\Exception $e) {
                // on affiche un message en haut de la page du formulaire
                $this->addFlash('error', "le formulaire n'est pas valide");
            }
        }

        return $this->render('film/new.html.twig', [
            'form' => $form,
        ]);
    }

    // route permettant l'afficher un film par son id
    // utilise un param converter (Film $film = null)
    // on le met a null par defaut pour eviter la levée d'exception si l'id n'existe pas en bdd
    #[Route('/{id}', name: 'app_film_show', methods: ['GET'])]
    public function show(Film $film = null): Response
    {
        // si l'id n'existe pas on affiche un message et une redirection
        if($film == null) {
            $this->flash("le film n'existe pas");
        }

        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }

    // route permettant la mise a jour d'un film
    // on utilise un param converter ( Film $film = null)
    // doctrine sait quand créer et quand modifier, il repère si la clé primaire est renseignée
    // si oui, il modifie l'objet, si non il crée l'objet
    #[Route('/{id}/edit', name: 'app_film_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Film $film = null, FilmRepository $filmRepository): Response
    {
        // dans le cas ou l'id n'existe pas
        if($film == null) {
            return $this->flash("le film n'existe pas");
        }

        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        // on vérifie si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // méthode permettant soit de créer, soit de mettre à jour, en fonction de si l'id est renseignée
            $filmRepository->save($film, true);

            return $this->redirectToRoute('app_film_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('film/edit.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    // route permettant la suppression
    // utilise un param converter pour récupérer l'objet en bdd
    #[Route('/{id}', name: 'app_film_delete', methods: ['POST'])]
    public function delete(Request $request, Film $film = null, FilmRepository $filmRepository): Response
    {
        // si l'id n'existe pas
        if($film == null) {
           $this->flash("l'id n'existe pas");
        }

        // si j'ai les droits pour supprimer
        if ($this->isCsrfTokenValid('delete' . $film->getId(), $request->request->get('_token'))) {
            $filmRepository->remove($film, true);
        }

        // redirection apres la suppression
        return $this->redirectToRoute('app_film_index', [], Response::HTTP_SEE_OTHER);
    }

    // methode privée permettant de gérer les messages flash
    // prend en paramètre le message à afficher dans twig
    private function flash($message): Response {
        $this->addFlash('error', $message);
        return $this->redirectToRoute('app_film_index');
    }
}
