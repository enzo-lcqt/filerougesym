<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FinaliserForm;
use App\Form\PaymentForm;
use App\Form\DeliveryFormType;
use App\Repository\PlatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commande;
use App\Entity\Detail;

#[Route('/finaliser', name: 'app_finaliser')]
class FinaliserController extends AbstractController
{
    public function index(Request $request, PlatsRepository $platsRepository, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $paymentForm = $this->createForm(PaymentForm::class);
        $deliveryForm = $this->createForm(DeliveryFormType::class);
        
        // Récupère les éléments du panier ici
        $panier = $session->get('panier', []); 

        $total = 0;
        foreach ($panier as $id => $quantity) {
            $plat = $platsRepository->find($id);
            $total += $plat->getPrix() * $quantity;
        }

        // Initialise le montant total du panier
        $montantTotalPanier = $total; //$request->get('total',0);

        // Crée le formulaire de facturation en passant le montant total comme option
        $finaliserForm = $this->createForm(FinaliserForm::class, null, ['montant_total' => $total]);

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
            $nouvelleCommande->setUtilisateur($this->getUser()); // Utilisateur actuellement connecté

            // Persiste la nouvelle commande dans la base de données
            $entityManager->persist($nouvelleCommande);
            $entityManager->flush();

            // Crée une instance de la classe Detail pour chaque plat dans le panier
            foreach ($panier as $id => $quantity) {
                $plat = $platsRepository->find($id);

                // Crée une nouvelle instance de la classe Detail
                $detail = new Detail();
                $detail->setQuantite($quantity);
                $detail->setPlats($plat);
                $detail->setCommande($nouvelleCommande);

                // Persiste la nouvelle instance de Detail dans la base de données
                $entityManager->persist($detail);
            }

            // Flush pour sauvegarder les détails dans la base de données
            $entityManager->flush();

            $session->remove('panier');

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('district/finaliser.html.twig', [
            'deliveryForm' => $deliveryForm->createView(),
            'FinaliserForm' => $finaliserForm->createView(),
            'PaymentForm' => $paymentForm->createView(),
        ]);
    }
}