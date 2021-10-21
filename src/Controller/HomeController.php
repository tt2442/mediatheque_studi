<?php

namespace App\Controller;

use DateTime;
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
     * @Route("/", name="home",options={"sitemap" = true})
     */
    public function index(LivreRepository $livreRepository, empruntRepository $empruntRepository): Response
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
        foreach ($result as $livre) {
            $emprunt = $empruntRepository->findBy(['Livre' => $livre->getId()], ['Datestart' => 'DESC']);
            if (count($emprunt) > 0) {
                $emprunt = $emprunt[0];
                $d1 = $emprunt->getDatestart();
                $d2 = new DateTime(date('Y-m-d'));
                $interval = $d1->diff($d2);
                $diff = $interval->format('%d');
                if ($diff > 3) {
                    $this->unreserver($livre, $emprunt);
                }
            }
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
     * @Route("/mes-emprunts", name="mes_emprunts",options={"sitemap" = true})
     */
    public function mesEmprunts(EmpruntRepository $empruntRepository): Response
    {
        $empruntsAll = $empruntRepository->findBy(['User' => $this->getUser(), 'Reserve' => true, 'Emptrunte' => true]);
        $emprunts = [];
        foreach ($empruntsAll as $emprunt) {
            $emprunts[$emprunt->getId()]['emprunt'] = $emprunt;
            $d1 = $emprunt->getDateend();
            $d2 = new DateTime(date('Y-m-d'));
            if ($d1 < $d2) {
                $emprunts[$emprunt->getId()]['retard'] = true;
            } else {
                $emprunts[$emprunt->getId()]['retard'] = false;
            }
        }

        return $this->render('home/mesEmprunts.html.twig', [
            'emprunts' => $emprunts,
        ]);
    }

    /**
     * @Route("/emprunts-user/{id}", name="emprunts_user")
     */
    public function empruntsUser(User $user, EmpruntRepository $empruntRepository): Response
    {
        $empruntsAll = $empruntRepository->findBy(['User' => $user, 'Reserve' => true, 'Emptrunte' => true]);
        $emprunts = [];
        foreach ($empruntsAll as $emprunt) {
            $emprunts[$emprunt->getId()]['emprunt'] = $emprunt;
            $d1 = $emprunt->getDateend();
            $d2 = new DateTime(date('Y-m-d'));
            if ($d1 < $d2) {
                $emprunts[$emprunt->getId()]['retard'] = true;
            } else {
                $emprunts[$emprunt->getId()]['retard'] = false;
            }
        }

        return $this->render('home/empruntsUser.html.twig', [
            'emprunts' => $emprunts,
            'user' => $user,
        ]);
    }
    /**
     * @Route("/emprunts/retard", name="emprunts_retard",options={"sitemap" = true})
     */
    public function empruntsEnRetard(EmpruntRepository $empruntRepository): Response
    {
        $empruntsAll = $empruntRepository->findBy(['Reserve' => true, 'Emptrunte' => true]);
        $emprunts = [];
        foreach ($empruntsAll as $emprunt) {
            $d1 = $emprunt->getDateend();
            $d2 = new DateTime(date('Y-m-d'));
            if ($d1 < $d2) {
                $emprunts[] = $emprunt;
            }
        }

        return $this->render('home/empruntsRetard.html.twig', [
            'emprunts' => $emprunts,
        ]);
    }


    /**
     * @Route("/rendre-livre/{id}/{user}", name="livre_rendre")
     */
    public function rendreLivre(Livre $livre, User $user, EmpruntRepository $empruntRepository): Response
    {
        if ($this->isGranted('ROLE_Administrateur') || $this->isGranted('ROLE_Employe')) {
            $emprunts = $empruntRepository->findBy(['Livre' => $livre->getId(), 'User' => $user->getId(), 'Reserve' => true, 'Emptrunte' => true]);
            foreach ($emprunts as $emprunt) {
                $this->unreserver($livre, $emprunt);
            }
            return $this->redirectToRoute('emprunts_user', ['id' => $user->getId()]);
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/rendre-livres/{id}/{user}", name="livres_rendre")
     */
    public function rendresLivre(Livre $livre, User $user, EmpruntRepository $empruntRepository): Response
    {
        if ($this->isGranted('ROLE_Administrateur') || $this->isGranted('ROLE_Employe')) {
            $emprunts = $empruntRepository->findBy(['Livre' => $livre->getId(), 'User' => $user->getId(), 'Reserve' => true, 'Emptrunte' => true]);
            foreach ($emprunts as $emprunt) {
                $this->unreserver($livre, $emprunt);
            }
            return $this->redirectToRoute('emprunts_retard');
        }

        return $this->redirectToRoute('home');
    }

    public function unreserver(Livre $livre, Emprunt $emprunt): Response
    {
        $emprunt->setReserve(false)->setEmptrunte(false);
        $livre->setReserve(false)->setDisponible(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($livre);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/emprunt/comfirm", name="emprunt_to_valid",options={"sitemap" = true})
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
     * @Route("/signup", name="signup", methods={"GET","POST"},options={"sitemap" = true})
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
     * @Route("/employe", name="employe",options={"sitemap" = true})
     */
    public function employe(): Response
    {
        return $this->render('home/employe.html.twig', []);
    }
}
