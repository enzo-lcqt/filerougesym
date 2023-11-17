<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FinaliserForm;
use App\Form\DeliveryFormType;
use App\Repository\PlatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commande;

#[Route('/finaliser', name: 'app_finaliser')]
class FinaliserController extends AbstractController
{
    
    public function index(Request $request, PlatsRepository $platsRepository, EntityManagerInterface $entityManager): Response
    {
        $deliveryForm = $this->createForm(DeliveryFormType::class);
        $finaliserForm = $this->createForm(FinaliserForm::class);

        $deliveryForm->handleRequest($request);
        $finaliserForm->handleRequest($request);

        if ($deliveryForm->isSubmitted() && $deliveryForm->isValid()) {
            // Logique de traitement pour le formulaire de livraison
            // ...

            // Redirige vers une autre page
            return $this->redirectToRoute('district/index.html.twig');
        }

        if ($finaliserForm->isSubmitted() && $finaliserForm->isValid()) {
            // Logique de traitement pour le formulaire de facturation
            // ...

            // Récupère les éléments du panier ici
            $panier = []; 

            // Initialise le montant total du panier
            $montantTotalPanier = 0;

            // Parcourt chaque élément du panier
            foreach ($panier as $element) {
                // Récupère le plat associé à l'élément du panier depuis le repository
                $plat = $platsRepository->find($element['plat_id']);

                // Ajoute le montant du plat multiplié par la quantité à total du panier
                $montantTotalPanier += $plat->getPrix() * $element['quantity'];
            }

            // Crée une nouvelle instance de la classe Commande
            $nouvelleCommande = new Commande();

            // Configure les propriétés de la commande
            $nouvelleCommande->setDateCommande(new \DateTime());
            $nouvelleCommande->setTotal($montantTotalPanier);
            $nouvelleCommande->setEtat('En attente');
            $nouvelleCommande->setUtilisateur($this->getUser()); // Utilisateur actuellement connecté

            // Persiste la nouvelle commande dans la base de données
            $entityManager->persist($nouvelleCommande);
            $entityManager->flush();

            // Redirige vers la page d'accueil
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('district/finaliser.html.twig', [
            'deliveryForm' => $deliveryForm->createView(),
            'FinaliserForm' => $finaliserForm->createView(),
        ]);
    }

}