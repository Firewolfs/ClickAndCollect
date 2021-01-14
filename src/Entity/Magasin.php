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
     * @ORM\OneToMany(targetEntity="App\Entity\Vendeur", mappedBy="magasin")
     */
    private $vendeurs;

    /**
     * @var Creneau[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Creneau", mappedBy="magasin")
     */
    private $creneaux;

    /**
     * Magasin constructor.
     */
    public function __construct() {
        $this->stocks = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->vendeurs = new ArrayCollection();
        $this->creneaux = new ArrayCollection();
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

    /**
     * @return mixed
     */
    public function getVendeurs()
    {
        return $this->vendeurs;
    }

    /**
     * @param mixed $vendeurs
     */
    public function setVendeurs($vendeurs): void
    {
        $this->vendeurs = $vendeurs;
    }

    /**
     * @return Creneau[]|ArrayCollection
     */
    public function getCreneaux()
    {
        return $this->creneaux;
    }

    /**
     * @param Creneau[]|ArrayCollection $creneaus
     */
    public function setCreneaux($creneaux): void
    {
        $this->creneaux = $creneaux;
    }

}
