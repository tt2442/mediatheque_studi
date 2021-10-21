<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"},options={"sitemap" = true})
     * @IsGranted("ROLE_Administrateur")
     */
    public function index(UserRepository $userRepository): Response
    {
        if (isset($_GET['search'])) {
            $getSearch = $_GET['search'];
        } else {
            $getSearch = '';
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
        $result = $userRepository->searchWhere($getSearch, $page, $nbrElementByPage);
        $nbrPage = intval(ceil(count($result) / $nbrElementByPage));
        if ($page > $nbrPage && $nbrPage > 0) {
            $page = $nbrPage;
            return $this->redirectToRoute('user_index', ['nbrElementByPage' => $nbrElementByPage, 'page' => $page, 'search' => $getSearch]);
        }
        return $this->render('user/index.html.twig', [
            'users' => $result,
            'search' => $getSearch,
            'page' => $page,
            'nbrElementByPage' => $nbrElementByPage,
            'nbrPage' => $nbrPage
        ]);
    }

    /**
     * @Route("/inactifs", name="user_index_inactive", methods={"GET"},options={"sitemap" = true})
     */
    public function inactive(UserRepository $userRepository): Response
    {
        if (isset($_GET['search'])) {
            $getSearch = $_GET['search'];
        } else {
            $getSearch = '';
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
        $result = $userRepository->searchWhereInactive($getSearch, $page, $nbrElementByPage);
        $nbrPage = intval(ceil(count($result) / $nbrElementByPage));
        if ($page > $nbrPage && $nbrPage > 0) {
            $page = $nbrPage;
            return $this->redirectToRoute('user_index_inactive', ['nbrElementByPage' => $nbrElementByPage, 'page' => $page, 'search' => $getSearch]);
        }
        return $this->render('user/index_inactive.html.twig', [
            'users' => $result,
            'search' => $getSearch,
            'page' => $page,
            'nbrElementByPage' => $nbrElementByPage,
            'nbrPage' => $nbrPage
        ]);
    }

    /**
     * @Route("/active/{id}", name="user_active", methods={"GET"})
     */
    public function active(User $user): Response
    {
        $user->setActive(true)->setRoles(['ROLE_Inscrit']);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('user_index_inactive', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"},options={"sitemap" = true})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->remove('password');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }
}
