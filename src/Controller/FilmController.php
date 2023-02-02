<?php

namespace App\Controller;

use App\Entity\Acteur;
use App\Entity\Film;
use App\Entity\Genre;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/film')]
class FilmController extends AbstractController
{
    #[Route('/', name: 'app_film_index', methods: ['GET'])]
    public function index(FilmRepository $filmRepository): Response
    {
        return $this->render('film/index.html.twig', [
            'films' => $filmRepository->findAll(),
        ]);
    }

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
               // on récupère les données du formulaire
               $data = $form->getData();

               // on parcourt la collection Acteurs
               foreach ($data->getActeurs() as $item) {
                   // on instancie un objet Acteur
                   $acteur = new Acteur();
                   // on lui soumet des valeurs depuis le formulaire
                   $acteur->setPrenom($item->getPrenom());
                   $acteur->setNom($item->getNom());
                   // on ajoute l'acteur à l'entité film (pour le many to many)
                   $film->addActeur($acteur);
               }

               // on parcourt la collection Genres
               foreach ($data->getGenres() as $item) {
                   // on instancie un objet Genre
                   $genre = new Genre();
                   // on lui soumet sa valeur depuis le formulaire
                   $genre->setType($item->getType());
                   // on ajoute le genre à l'entité film (pour le many to many)
                   $film->addGenre($genre);
               }

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


    #[Route('/{id}', name: 'app_film_show', methods: ['GET'])]
    public function show(Film $film): Response
    {
        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_film_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Film $film, FilmRepository $filmRepository): Response
    {
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filmRepository->save($film, true);

            return $this->redirectToRoute('app_film_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('film/edit.html.twig', [
            'film' => $film,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_film_delete', methods: ['POST'])]
    public function delete(Request $request, Film $film, FilmRepository $filmRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$film->getId(), $request->request->get('_token'))) {
            $filmRepository->remove($film, true);
        }

        return $this->redirectToRoute('app_film_index', [], Response::HTTP_SEE_OTHER);
    }
}
