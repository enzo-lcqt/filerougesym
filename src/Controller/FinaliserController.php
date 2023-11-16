<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FinaliserForm;
use App\Form\deliveryFormType;

#[Route('/finaliser', name: 'app_finaliser')]
class FinaliserController extends AbstractController
{
    
    public function index(Request $request): Response
    {
        $deliveryForm = $this->createForm(DeliveryFormType::class);
        $finaliserForm = $this->createForm(FinaliserForm::class);

        $deliveryForm->handleRequest($request);
        $finaliserForm->handleRequest($request);

        if ($deliveryForm->isSubmitted() && $deliveryForm->isValid()) {
            // Logique de traitement pour le formulaire de livraison
            // Par exemple, rediriger vers une autre page
            return $this->redirectToRoute('district/index.html.twig');
        }

        if ($finaliserForm->isSubmitted() && $finaliserForm->isValid()) {
            // Logique de traitement pour le formulaire de facturation
            // Par exemple, rediriger vers une autre page
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('district/finaliser.html.twig', [
            'deliveryForm' => $deliveryForm->createView(),
            'FinaliserForm' => $finaliserForm->createView(),
        ]);
    }

    
}