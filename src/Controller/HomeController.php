<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Livre;
use App\Form\UserType;
use App\Entity\Emprunt;
use App\Form\EmpruntType;
use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use App\Repository\EmpruntRepository;
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
    public function index(LivreRepository $livreRepository): Response
    {
        if (isset($_GET['search'])) {
            $titre = $_GET['search'];
        } else {
            $titre = '';
        }
        if (isset($_GET['type'])) {
            $type = $_GET['type'];
        } else {
            $type = '';
        }
        if (isset($_GET['genre'])) {
            $genre = $_GET['genre'];
        } else {
            $genre = 0;
        }
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }
        if (isset($_GET['nbrElementByPage'])) {
            $nbrElementByPage = $_GET['nbrElementByPage'];
        } else {
            $nbrElementByPage = 10;
        }
        $preresult = $livreRepository->searchWhere($titre, $type, $page, $nbrElementByPage);
        $result = [];
        if ($genre == 0) {
            $result = $preresult;
        } else {
            foreach ($preresult as $livre) {
                foreach ($livre->getGenres() as $genreOfLivre) {
                    if ($genre == $genreOfLivre->getId()) {
                        $result[] = $livre;
                    }
                }
            }
        }
        $nbrPage = intval(ceil(count($result) / $nbrElementByPage));
        if ($page > $nbrPage && $nbrPage > 0) {
            $page = $nbrPage;
            return $this->redirectToRoute('livre_index', ['nbrElementByPage' => $nbrElementByPage, 'page' => $page, 'search' => $titre, 'type' => $type, 'genre' => $genre]);
        }
        return $this->render('home/index.html.twig', [
            'livres' => $result,
            'genres' => $this->genres,
            'search' => $titre,
            'type' => $type,
            'genre' => $genre,
            'page' => $page,
            'nbrElementByPage' => $nbrElementByPage,
            'nbrPage' => $nbrPage
        ]);
    }

    /**
     * @Route("/voir-livre/{id}", name="livre_voir")
     */
    public function voirLivre(Livre $livre): Response
    {
        return $this->render('livre/voir.html.twig', [
            'livre' => $livre
        ]);
    }

    /**
     * @Route("/emprunt", name="emprunt_to_valid")
     */
    public function empruntToValid(EmpruntRepository $empruntRepository): Response
    {
        return $this->render('emprunt.html.twig', [
            'emprunts' => $empruntRepository->findBy(['Reserve' => true, 'Emptrunte' => false])
        ]);
    }

    /**
     * @Route("/reserver-livre/{id}", name="reserver")
     */
    public function reserver(Livre $livre): Response
    {
        $emprunt = new Emprunt();
        $emprunt->setReserve(true)
            ->setEmptrunte(false)
            ->setDatestart(new \Datetime)
            ->setLivre($livre)
            ->setUser($this->getUser());
        $livre->setReserve(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($emprunt);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }


    /**
     * @Route("/confirmEmprunt/{id}", name="comfirm_emprunt")
     */
    public function comfirmEmprunt(Request $request, Emprunt $emprunt): Response
    {
        $form = $this->createForm(EmpruntType::class, $emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emprunt->setReserve(true)
                ->setEmptrunte(true);
            $livre = $emprunt->getLivre();
            $livre->setDisponible(false);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($livre);
            $entityManager->persist($emprunt);
            $entityManager->flush();
            return $this->redirectToRoute('emprunt_to_valid');
        }

        return $this->renderForm('empruntComfirm.html.twig', [
            'form' => $form
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
