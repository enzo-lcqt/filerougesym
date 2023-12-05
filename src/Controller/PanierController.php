<?php

namespace App\Controller;

use App\Entity\Plats;
use App\Repository\PlatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/panier', name: 'panier_')]
class PanierController extends AbstractController
{
    
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, PlatsRepository $platsRepository)
    {
        $panier = $session->get('panier', []);

        //On initialise des variables 
        $data = [];
        $total = 0;

        foreach($panier as $id => $quantity) {
            $plat = $platsRepository->find($id);

            $data[] = [
                'plat' => $plat,
                'quantity' => $quantity
            ];
            $total += $plat->getPrix() * $quantity;
        }

        //compact() crée un tableau à partir de variables et de leur valeur
        return $this->render('district/panier.html.twig', compact('data', 'total'));
    }

    #[Route('/panier/ajout/{id}', name: 'add_panier')]
    public function add(Plats $plats, SessionInterface $session)
    {
        //On récupère l'id du produit
        $id = $plats->getId();

        //On récupère le panier existant
        $panier = $session->get('panier', []);

        //On ajoute le produit dans le panier s'il n'y est pas encore
        //Sinon on incrémente sa quantité
        if(empty($panier[$id])){
            $panier[$id] = 1;
        } else{
            $panier[$id]++;
        }

        $session->set('panier', $panier);

        //On redirige vers la page du panier
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/panier/remove/{id}', name: 'remove_panier')]
    public function remove(Plats $plats, SessionInterface $session)
    {
        //On récupère l'id du produit
        $id = $plats->getId();

        //On récupère le panier existant
        $panier = $session->get('panier', []);

        //On retire le produit du panier s'il n'y a qu'un plat
        //Sinon on décrémente sa quantité
        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            } else{
                unset($panier[$id]);
            }
        }

        $session->set('panier', $panier);

        //On redirige vers la page du panier
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/panier/delete/{id}', name: 'delete_panier')]
    public function delete(Plats $plats, SessionInterface $session)
    {
        //On récupère l'id du produit
        $id = $plats->getId();

        //On récupère le panier existant
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
                unset($panier[$id]);
        }

        $session->set('panier', $panier);

        //On redirige vers la page du panier
        return $this->redirectToRoute('panier_index');
    }

    #[Route('/panier/empty', name: 'empty_panier')]
    public function empty(SessionInterface $session)
    {
        $session->remove('panier');

        //On redirige vers la page du panier
        return $this->redirectToRoute('panier_index');
    }

    
}