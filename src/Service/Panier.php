<?php

namespace App\Service;

class Panier
{
    private $contenu = [];

    public function ajouterPlat($platId)
    {
        // Ajoutez le plat au panier, par exemple en stockant son ID et sa quantité
    }

    public function retirerPlat($platId)
    {
        // Retirez le plat du panier
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function getTotal()
    {
        // Calculez le montant total en fonction du contenu du panier
    }
}