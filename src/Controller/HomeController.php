<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(LivreRepository $livreRepository): Response
    {
        $livres = $livreRepository->findBy(['Disponible' => true]);
        return $this->render('home/index.html.twig', [
            'livres' => $livres,
        ]);
    }

    /**
     * @Route("/membre", name="membre")
     */
    public function membre(): Response
    {
        return $this->render('home/membre.html.twig', []);
    }
}
