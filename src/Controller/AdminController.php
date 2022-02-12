<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\User;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/partie_admin")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/", name="liste_produit", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }
    /**
     * @Route("/users/{id}", name="partie_admin/users/",methods={"GET"})
     */
    public function show_user(user $user): Response
    {
        return $this->render('admin/show_user.html.twig', [
            'user' => $user,
        ]);
    }




    /**
     * @Route("/users", name="app_users", methods={"GET"})
     */
    public function users(UserRepository $userRepository): Response
    {
        return $this->render('admin/users.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }




    /**
     * @Route("/{id}", name="admin_show2", methods={"GET"})
     */
    public function showadmin(Produit $produit): Response
    {
        return $this->render('admin/show.html.twig', [
            'produit' => $produit,
        ]);
    }












    /**
     * @Route("/{id}/edit", name="app_produit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produit $produit): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('liste_produit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }



    /**
     * @Route("/delete/{id}", name="produit_delete", methods={"POST"})
     */
    public function delete(Request $request, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('liste_produit', [], Response::HTTP_SEE_OTHER);
    }










}
