<?php

namespace App\Entity;

use App\Repository\MagasinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MagasinRepository::class)
 */
class Magasin
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @var Stocks[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Stocks", mappedBy="magasin")
     */
    private $stocks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commande", mappedBy="magasin")
     */
    private $commandes;

    /**
     * Magasin constructor.
     */
    public function __construct() {
        $this->stocks = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Stocks[]
     */
    public function getStocks()
    {
        return $this->stocks;
    }

    /**
     * @param mixed $stocks
     */
    public function setStocks(ArrayCollection $stocks): void
    {
        $this->stocks = $stocks;
    }

    /**
     * @return ArrayCollection
     */
    public function getCommandes(): ArrayCollection
    {
        return $this->commandes;
    }

    /**
     * @param ArrayCollection $commandes
     */
    public function setCommandes(ArrayCollection $commandes): void
    {
        $this->commandes = $commandes;
    }

}
