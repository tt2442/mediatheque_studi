<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/livre")
 */
class LivreController extends AbstractController
{
    /**
     * @Route("/", name="livre_index", methods={"GET"})
     */
    public function index(LivreRepository $livreRepository, GenreRepository $genreRepository): Response
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
        return $this->render('livre/index.html.twig', [
            'livres' => $result,
            'search' => $titre,
            'type' => $type,
            'genre' => $genre,
            'genres' => $genreRepository->findAll(),
            'page' => $page,
            'nbrElementByPage' => $nbrElementByPage,
            'nbrPage' => $nbrPage
        ]);
    }



    /**
     * @Route("/new", name="livre_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $fichierFile */
            $fichierFile = $form->get('Img')->getData();

            // this condition is needed because the 'fichier' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($fichierFile) {
                $originalFilename = pathinfo($fichierFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $fichierFile->guessExtension();

                // Move the file to the directory where fichiers are stored
                try {
                    $fichierFile->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $livre->setImg($newFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="livre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Livre $livre, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $fichierFile */
            $fichierFile = $form->get('Img')->getData();

            // this condition is needed because the 'fichier' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($fichierFile) {
                $originalFilename = pathinfo($fichierFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $fichierFile->guessExtension();

                // Move the file to the directory where fichiers are stored
                try {
                    $fichierFile->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $livre->setImg($newFilename);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="livre_delete", methods={"POST"})
     */
    public function delete(Request $request, Livre $livre): Response
    {
        if ($this->isCsrfTokenValid('delete' . $livre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($livre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('livre_index', [], Response::HTTP_SEE_OTHER);
    }
}
