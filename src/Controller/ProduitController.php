<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="produit_index")
     */
    public function index(ProduitRepository $produitRepository , Request $request): Response
    {
        $produit = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class,$propertySearch);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
            //on récupère le nom de produit a recherche
            $nom = $propertySearch->getNom();
            if ($nom!="")
                //si on a fourni nom de produit
                $produit= $this->getDoctrine()->getRepository(Produit::class)->findBy(['Nom' => $nom] );
            else
                //si si aucun  produit n'est fourni on affiche tous les articles
                $produit = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        }
        return  $this->render('produit/index.html.twig',[
            'form' =>$form->createView(),
            'produits' => $produit,

        ]);
    }

    

    /**
     * @Route("/consulter/{id}", name="produit/consulter", methods={"GET"})
     */
    public function consulter(Produit $produit): Response
    {
        return $this->render('produit/show_c.html.twig', [
            'produit' => $produit,
        ]);
    }






}
