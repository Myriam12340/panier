<?php

namespace App\Controller;
use App\Entity\Produit;
use App\Entity\User;
use App\Form\ProduitType;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }



    /**
     * @Route("/phomme", name="produit_homme")
     */
    public function Phomme(ProduitRepository $produitRepository , Request $request): Response
    {
        $produit = $this->getDoctrine()->getRepository(Produit::class)->findBy(['type' => 'homme']);


        return  $this->render('produit/homme.html.twig',[

            'produits' => $produit,

        ]);
    }
    /**
     * @Route("/pfemme", name="produit_femme")
     */
    public function Pfemme(ProduitRepository $produitRepository , Request $request): Response
    {
        $produit = $this->getDoctrine()->getRepository(Produit::class)->findBy(['type' => 'femme']);


        return  $this->render('produit/femme.html.twig',[

            'produits' => $produit,

        ]);
    }
    /**
     * @Route("/new", name="app_produit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('liste_produit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/produit/{id}", name="/partie_admin/show",methods={"GET"})
     */
    public function show(Produit $produit): Response
    {
        return $this->render('admin/show_c.html.twig', [
            'produit' => $produit,
        ]);
    }



}
