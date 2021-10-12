<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(LivreRepository $livreRepository, GenreRepository $genreRepository): Response
    {
        $livres = $livreRepository->findBy(['Disponible' => true]);
        $genres = $genreRepository->findAll();
        return $this->render('home/index.html.twig', [
            'livres' => $livres,
            'genres' => $genres,
        ]);
    }

    /**
     * @Route("/employe", name="employe")
     */
    public function employe(): Response
    {
        return $this->render('home/employe.html.twig', []);
    }
}
