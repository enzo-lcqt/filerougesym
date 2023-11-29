<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FinaliserForm;
use App\Form\DeliveryFormType;
use App\Entity\Detail;
use App\Repository\PlatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commande;

#[Route('/finaliser', name: 'app_finaliser')]
class FinaliserController extends AbstractController
{
    public function index(Request $request, PlatsRepository $platsRepository, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $deliveryForm = $this->createForm(DeliveryFormType::class);
        
        // Récupère les éléments du panier ici
        $panier = $session->get('panier', []); 

        // Initialise le montant total du panier
        $montantTotalPanier = $request->get('total', 0);

        // Parcourt chaque élément du panier
        foreach ($panier as $element) {
            // Vérifie si la clé 'plat_id' existe dans l'élément du panier
            if (isset($element['plat_id'])) {
                // Récupère le plat associé à l'élément du panier depuis le repository
                $plat = $platsRepository->find($element['plat_id']);

                // Vérifie si le plat existe avant de travailler avec lui
                if ($plat) {
                    // Ajoute le montant du plat multiplié par la quantité à total du panier
                    $montantTotalPanier += $plat->getPrix() * $element['quantity'];
                }
            }
        }

        // Déclaration de débogage pour afficher le montant total
        dump($request->get('total'));

        // Crée le formulaire de facturation en passant le montant total comme option
        $finaliserForm = $this->createForm(FinaliserForm::class, null, ['montant_total' => $request->get('total')]);

        $deliveryForm->handleRequest($request);
        $finaliserForm->handleRequest($request);

        if ($deliveryForm->isSubmitted() && $deliveryForm->isValid()) {
            // Logique de traitement pour le formulaire de livraison
            // ...

            // Redirige vers une autre page
            return $this->redirectToRoute('district/finalisercommande.html.twig');
        }

        if ($finaliserForm->isSubmitted() && $finaliserForm->isValid()) {
            // Logique de traitement pour le formulaire de facturation
            // ...

            // Crée une nouvelle instance de la classe Commande
            $nouvelleCommande = new Commande();

            // Configure les propriétés de la commande
            $nouvelleCommande->setDateCommande(new \DateTime());
            $nouvelleCommande->setTotal($montantTotalPanier);
            $nouvelleCommande->setEtat('En attente');
            $nouvelleCommande->setUtilisateur($this->getUser()); 

            // Persiste la nouvelle commande dans la base de données
            $entityManager->persist($nouvelleCommande);

            foreach ($panier as $element) {
                // Vérifie si $element est un tableau et a une clé 'plat_id'
                if (is_array($element) && isset($element['plat_id'])) {
                    // Récupère le plat associé à l'élément du panier depuis le repository
                    $plat = $platsRepository->find($element['plat_id']);
            
                    // Vérifie si le plat existe avant de travailler avec lui
                    if ($plat) {
                        // Crée une nouvelle instance de la classe Detail
                        $detail = new Detail();
            
                        // Configure les propriétés du détail (relation entre Commande et Plats)
                        $detail->setPlats($plat);
                        $detail->setQuantite($element['quantity']);
            
                        // Ajoute le détail à la commande
                        $nouvelleCommande->addDetail($detail);
            
                        // Persistez le détail
                        $entityManager->persist($detail);
                    }
                }
            }

        
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