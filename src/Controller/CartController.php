<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session, ProduitRepository $prouditRepository )
    {
        $panier = $session->get("panier", []);


        $dataPanier = [];
        $total = 0;
        $nb =0;

        foreach($panier as $id => $quantite){
            $produit = $prouditRepository->find($id);
            $dataPanier[] = [
                "produit" => $produit,
                "quantite" => $quantite
            ];
            $nb =$nb+1;


            $total += $produit->getPrix() * $quantite;
        }

        return $this->render('cart/index.html.twig',["dataPanier"=>$dataPanier, "total"=>$total, "nb"=>$nb]);
    }

    /**
     * @Route("/ajouter/{id}", name="ajouter")
     */
    public function ajouter(Produit $produit, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $produit->getId();

        if(!empty($panier[$id])){
            if ($panier[$id]< $produit->getQte()) {
                $panier[$id]++;
            }
             else{
                 $this->addFlash('error', 'Repture de stock '.$produit->getNom());
             }
        }else{
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }





    /**
     * @Route("/add/{id}", name="add")
     */
    public function add(Produit $produit, SessionInterface $session ,ProduitRepository $produitRepository)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $produit->getId();


        if(!empty($panier[$id]) ){
            if ($panier[$id]< $produit->getQte())
            {$panier[$id]++;}
            else
                $this->addFlash('error', 'Repture de stock '.$produit->getNom());


        }else{
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("produit_index");
    }

    /**
     * @Route("/supprime1/{id}", name="supprime1")
     */
    public function remove(Produit $produit, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $produit->getId();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function delete(Produit $produit, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $produit->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/vider_panier", name="vider_panier")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("cart_index");
    }

//POUR AJOUTER AU PANIER DANS L INTERFACE HOMME
    /**
     * @Route("/addH/{id}", name="addh")
     */
    public function addH(Produit $produit, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $produit->getId();

        if(!empty($panier[$id]) ){
            if ($panier[$id]< $produit->getQte())
            {$panier[$id]++;}
            else
                $this->addFlash('error', 'Repture de stock '.$produit->getNom());


        }else{
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("produit_homme");
    }
//POUR AJOUTER AU PANIER DANS L INTERFACE HOMME
    /**
     * @Route("/addF/{id}", name="addf")
     */
    public function addf(Produit $produit, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $produit->getId();

        if(!empty($panier[$id]) ){
            if ($panier[$id]< $produit->getQte())
            {$panier[$id]++;}
            else
                $this->addFlash('error', 'Repture de stock '.$produit->getNom());


        }else{
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("produit_femme");
    }

    /**
     * @Route("/valider", name="valider")
     */
    public function valider(SessionInterface $session, ProduitRepository $prouditRepository  )
    {
        $panier = $session->get("panier", []);


        $dataPanier = [];
        $total = 0;
        $nb =0;

        foreach($panier as $id => $quantite){

            $produit = $prouditRepository->find($id);



            $produit->setQte($produit->getQte()-$quantite);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $session->remove("panier");

        }

        return $this->render('cart/index.html.twig',["dataPanier"=>$dataPanier, "total"=>$total, "nb"=>$nb]);
    }


}
