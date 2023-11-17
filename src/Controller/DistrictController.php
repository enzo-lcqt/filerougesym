<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Service\Panier;
use App\Repository\PlatsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DistrictController extends AbstractController
{
    
    private $categorieRepo;
    private $platRepo;

    public function __construct(CategorieRepository $categorieRepo, PlatsRepository $platRepo)
    {
        $this->categorieRepo = $categorieRepo;
        $this->platRepo = $platRepo;
    }

    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        $categories = $this->categorieRepo->findAll();
        dump($categories);

        return $this->render('district/index.html.twig', [
            'categories' => $categories

        ]);
    }

    #[Route('/plats', name: 'app_plats')]
    public function plats(): Response
    {
        //on appelle la fonction `findAll()` du repository de la classe `Plat` afin de récupérer tous les plats de la base de données;
        $plats = $this->platRepo->findAll();
        dump($plats);

        return $this->render('district/plats.html.twig', [

            'plats' => $plats
        ]);
    }

    #[Route('/categories', name: 'app_categories')]
    public function categories(): Response
    {
        $categories = $this->categorieRepo->findAll();
        dump($categories);
        return $this->render('district/categories.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/plats/{categorie_id}', name: 'app_plats_categorie')]
    public function platsCategorie(int $categorie_id, CategorieRepository $categorieRepo): Response
    {
        // je récupère la categorie correspondant à l'id
        $categorie = $this->categorieRepo->find($categorie_id);
        dump($categorie);

        $plats = $categorie->getPlats();
        return $this->render('district/plats.html.twig', [
            'categories' => $categorie,
            'plats' => $plats,
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, Security $security, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContactType::class);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
        
            // Créez un nouvel objet Utilisateur et enregistrez les données du formulaire
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($formData['nom']);
            $utilisateur->setPrenom($formData['prenom']);
            $utilisateur->setTelephone($formData['telephone']);
            $utilisateur->setEmail($formData['email']);
            $utilisateur->setPassword($formData['password']);
            $utilisateur->setVille($formData['ville']);
            $utilisateur->setAdresse($formData['adresse']);
            $utilisateur->setCodePostal($formData['code_postal']);
            
            // Utilisez le rôle de l'utilisateur connecté
           
            
            // Utilisez l'EntityManager pour persister et flush l'objet Utilisateur
            $entityManager->persist($utilisateur);
            $entityManager->flush();
        
            // Redirigez l'utilisateur vers la page de succès après la soumission réussie.
            return $this->redirectToRoute('app_success');
        }
        
        return $this->render('district/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    
    #[Route('/contact/success', name: 'app_success')]
    public function success(): Response
    {
        // Cette action affiche une page de succès après une soumission réussie.
        return $this->render('district/success.html.twig');
    }

    #[Route('/mention', name: 'app_mention')]
    public function mention(): Response
    {
        // Cette action affiche une page de succès après une soumission réussie.
        return $this->render('district/mentions.html.twig');
    }
    
    #[Route('/politique', name: 'app_politique')]
    public function politique(): Response
    {
        // Cette action affiche une page de succès après une soumission réussie.
        return $this->render('district/politique.html.twig');
    }

    #[Route('/finalisercommande', name: 'app_finalisercommande')]
    public function finalisercommande(): Response
    {
        // Cette action affiche une page de succès après une soumission réussie.
        return $this->render('district/finalisercommande.html.twig');
    }

    }



