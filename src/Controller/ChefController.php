<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Plats;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Form\PlatsType;

class ChefController extends AbstractController
{
    #[Route("/chef/dashboard", name: "chef_dashboard")]
    public function dashboard(Request $request, EntityManagerInterface $entityManager): Response
    {

        // Récupère tous les plats depuis la base de données
        $plats = $entityManager->getRepository(Plats::class)->findAll();

        // Crée une nouvelle instance de l'entité Plats et un formulaire associé
        $plat = new Plats();
        $form = $this->createForm(PlatsType::class, $plat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérez le téléchargement du fichier ici
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                // Générez un nom unique pour le fichier
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    // Déplacez le fichier vers le répertoire approprié
                    $imageFile->move(
                        $this->getParameter('images_directory'), 
                        $newFilename
                    );

                    // Stockez le chemin complet dans la base de données
                    $plat->setImage('img/food/' . $newFilename);

                } catch (FileException $e) {
                    // Gérer l'exception, par exemple, afficher un message à l'utilisateur
                    $this->addFlash('error', 'Une erreur s\'est produite lors du téléchargement du fichier.');
                    return $this->redirectToRoute('chef_dashboard');
                }
            }

            // Enregistrez les modifications dans la base de données
            $entityManager->persist($plat);
            $entityManager->flush();

            $this->addFlash('success', 'Le plat a été ajouté avec succès.');

            return $this->redirectToRoute('chef_dashboard');
        }

        // Rend la vue avec le formulaire et la liste des plats
        return $this->render('chef/dashboard.html.twig', [
            'form' => $form->createView(),
            'plats' => $plats,
        ]);
    }


    #[Route('/chef/modifier/{id}', name:'modifier_plat')]
    public function modifierPlat(Request $request, EntityManagerInterface $entityManager, Plats $plat): Response
    {
        // Crée le formulaire pour modifier le plat
        // En passant ['modifier_image' => false], on désactive le champ image pour la modification
        $form = $this->createForm(PlatsType::class, $plat, ['modifier_image' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ...

            // Mise à jour de l'image uniquement si le champ image est inclus dans le formulaire
            if ($form->has('image')) {
                $imageFile = $form->get('image')->getData();

                if ($imageFile) {
                    // Gestion du téléchargement de la nouvelle image
                }
            }


            // Enregistre les modifications dans la base de données
            $entityManager->flush();

            $this->addFlash('success', 'Le plat a été modifié avec succès.');

            return $this->redirectToRoute('chef_dashboard');
        }

        // Rend la vue avec le formulaire de modification
        return $this->render('chef/modifier_plat.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route("/chef/supprimer/{id}", name:"supprimer_plat")]
    public function supprimerPlat(EntityManagerInterface $entityManager, Plats $plat): Response
    {
        // Supprime le plat de la base de données
        $entityManager->remove($plat);
        $entityManager->flush();

        $this->addFlash('success', 'Le plat a été supprimé avec succès.');

        return $this->redirectToRoute('chef_dashboard');
    }
}
