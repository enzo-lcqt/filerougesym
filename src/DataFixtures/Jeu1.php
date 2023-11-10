<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Categorie;
use App\Entity\Plats;
use App\Entity\Detail;
use App\Entity\Commande;
use App\Entity\Utilisateur;


class Jeu1 extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Catégorie 1
$categorie1 = new Categorie();
$categorie1->setLibelle('Burger');
$categorie1->setImage('img/food/burgercat.jpg');
$categorie1->setActive('Yes');
$manager->persist($categorie1);

// Catégorie 2
$categorie2 = new Categorie();
$categorie2->setLibelle('Pizza');
$categorie2->setImage('img/food/pizzacat.jpg');
$categorie2->setActive('Yes');
$manager->persist($categorie2);

// Catégorie 3
$categorie3 = new Categorie();
$categorie3->setLibelle('Pasta');
$categorie3->setImage('img/food/pastacat.png');
$categorie3->setActive('Yes');
$manager->persist($categorie3);

// Catégorie 4
$categorie4 = new Categorie();
$categorie4->setLibelle('Asian Food');
$categorie4->setImage('img/food/asiancat.jpg');
$categorie4->setActive('Yes');
$manager->persist($categorie4);

// Catégorie 5
$categorie5 = new Categorie();
$categorie5->setLibelle('Wraps');
$categorie5->setImage('img/food/composer.jpg');
$categorie5->setActive('Yes');
$manager->persist($categorie5);

// Catégorie 6
$categorie6 = new Categorie();
$categorie6->setLibelle('Sandwich');
$categorie6->setImage('img/food/sandpoulet.jpg');
$categorie6->setActive('Yes');
$manager->persist($categorie6);


$plat1 = new Plats();
$plat1->setLibelle('Hamburger');
$plat1->setDescription('Un steack haché, une rondelle de cornichon, des oignons, des rondelles de tomates, du bacon et du cheddar fondant');
$plat1->setPrix('10.00');
$plat1->setImage('img/food/hamburger.jpg');
$plat1->setCategorie($categorie1); 
$plat1->setActive('Oui');
$manager->persist($plat1);

$plat2 = new Plats();
$plat2->setLibelle('margherita');
$plat2->setDescription('garnie de tomates, de mozzarella, de basilic frais, de sel et dhuile dolive.');
$plat2->setPrix('12.00');
$plat2->setImage('img/food/pizza-margherita.jpg');
$plat2->setCategorie($categorie2); 
$plat2->setActive('Oui');
$manager->persist($plat2);

$plat3 = new Plats();
$plat3->setLibelle('spaghetti');
$plat3->setDescription('Spaghettis, Viande de boeuf hachée, tomate pelée, oignon, ail, thym, laurier, huile dolive.');
$plat3->setPrix('10.00');
$plat3->setImage('img/food/spaghetti-legumes.jpg');
$plat3->setCategorie($categorie3); 
$plat3->setActive('Oui');
$manager->persist($plat3);

$plat4 = new Plats();
$plat4->setLibelle('sushis');
$plat4->setDescription('riz, saumon, algue, avocat,');
$plat4->setPrix('30.00');
$plat4->setImage('img/food/sushis.png');
$plat4->setCategorie($categorie4); 
$plat4->setActive('Oui');
$manager->persist($plat4);

$plat5 = new Plats();
$plat5->setLibelle('wrap poulet');
$plat5->setDescription('wrap a la viande de poulet');
$plat5->setPrix('8.00');
$plat5->setImage('img/food/Food-Name-3461.jpg 	');
$plat5->setCategorie($categorie5); 
$plat5->setActive('Oui');
$manager->persist($plat5);

$user1 = new Utilisateur();
$user1->setEmail("test1@test.com");
$user1->setPassword("123456");
$user1->setNom("test");
$user1->setPrenom("test2");
$user1->setTelephone("0123456789");
$user1->setAdresse("3 rue des cailloux");
$user1->setCp("80000");
$user1->setVille("amiens");
$user1->setRoles("Role_USER");
$manager->persist($user1);


$user2 = new Utilisateur();
$user2->setEmail("test1@test.com");
$user2->setPassword("123456");
$user2->setNom("test222");
$user2->setPrenom("test2");
$user2->setTelephone("0123456789");
$user2->setAdresse("3 rue des cailloux");
$user2->setCp("80000");
$user2->setVille("amiens");
$user2->setRoles("Role_USER");
$manager->persist($user2);

$comm1 = new Commande();
$comm1->setDateCommande(new \DateTime("2020-11-30 03:52:43"));
$comm1->setTotal("10");
$comm1->setEtat("en cours");
$manager->persist($comm1);

$comm2 = new Commande();
$comm2->setDateCommande(new \DateTime("2020-11-30 03:52:43"));
$comm2->setTotal("20");
$comm2->setEtat("livré");
$manager->persist($comm2);

$comm3 = new Commande();
$comm3->setDateCommande(new \DateTime("2020-11-30 03:52:43"));
$comm3->setTotal("30");
$comm3->setEtat("annulée");
$manager->persist($comm3);






// Exécuter les opérations de persist
$manager->flush();
    }
}
