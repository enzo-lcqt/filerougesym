<?php

namespace App\Entity;

use App\Repository\DetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailRepository::class)]
class Detail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'detail')]
    private ?Plats $plats = null;

    #[ORM\ManyToOne(inversedBy: 'details')]
    private ?Commande $commande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPlats(): ?Plats
    {
        return $this->plats;
    }

    public function setPlats(?Plats $plats): static
    {
        $this->plats = $plats;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    /**
     * @param Commande|null $commande
     * @return $this
     */

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }
}
