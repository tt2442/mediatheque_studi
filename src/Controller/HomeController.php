<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{
    protected $genres;

    public function __construct(GenreRepository $genreRepository)
    {
        $this->genres = $genreRepository->findAll();
    }


    /**
     * @Route("/", name="home")
     */
    public function index(LivreRepository $livreRepository, GenreRepository $genreRepository): Response
    {
        $livres = $livreRepository->findBy(['Disponible' => true]);
        return $this->render('home/index.html.twig', [
            'livres' => $livres,
            'genres' => $this->genres,
        ]);
    }

    /**
     * @Route("/signup", name="signup", methods={"GET","POST"})
     */
    public function signup(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->remove('roles')->remove('active');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles(['ROLE_Habitant']);
            $user->setActive(false);
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Inscription réussi');

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('security/signup.html.twig', [
            'user' => $user,
            'genres' => $this->genres,
            'form' => $form,
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
