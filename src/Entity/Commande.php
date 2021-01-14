<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=false, options={"default" = 0})
     */
    private $prix_total;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CommandeStatut", inversedBy="commandes")
     */
    private $etat;

    /**
     * @ORM\OneToOne(targetEntity="Creneau", inversedBy="commande")
     * @ORM\JoinColumn(name="id_creneau", referencedColumnName="id_creneau")
     */
    private $creneau;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LigneCommande", mappedBy="commande")
     */
    private $commande_lignes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="commandes")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Magasin", inversedBy="commandes")
     */
    private $magasin;

    /**
     * Commande constructor.
     */
    public function __construct() {
        $this->commande_lignes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPrixTotal()
    {
        return $this->prix_total;
    }

    /**
     * @param mixed $prix_total
     */
    public function setPrixTotal($prix_total): void
    {
        $this->prix_total = $prix_total;
    }

    /**
     * @return CommandeStatut
     */
    public function getEtat(): ?CommandeStatut
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat): void
    {
        $this->etat = $etat;
    }

    /**
     * @return Collection|LigneCommande[]
     */
    public function getCommandeLignes()
    {
        return $this->commande_lignes;
    }

    /**
     * @param ArrayCollection $commande_lignes
     */
    public function setCommandeLignes(ArrayCollection $commande_lignes): void
    {
        $this->commande_lignes = $commande_lignes;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client): void
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function getMagasin()
    {
        return $this->magasin;
    }

    /**
     * @param mixed $magasin
     */
    public function setMagasin($magasin): void
    {
        $this->magasin = $magasin;
    }

    /**
     * @return mixed
     */
    public function getCreneau()
    {
        return $this->creneau;
    }

    /**
     * @param mixed $creneau
     */
    public function setCreneau($creneau): void
    {
        $this->creneau = $creneau;
    }

}
