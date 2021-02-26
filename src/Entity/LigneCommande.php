<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LigneCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneCommandeRepository::class)
 * @ApiResource(
 *     normalizationContext={"list"},
 *     collectionOperations={
 *         "post"={"method"="POST", "access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get" = { "security" = "is_granted('ROLE_USER')" },
 *          "put" = { "security" = "is_granted('ROLE_ADMIN')" },
 *          "delete" = { "security" = "is_granted('ROLE_ADMIN')" }
 *     },
 * )
 */
class LigneCommande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commande", inversedBy="commande_lignes")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $commande;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit", inversedBy="command_lignes")
     */
    private $produit;

    /**
     * @ORM\Column(type="float", nullable=false, options={"default" = 0})
     */
    private $prix_tot;

    /**
     * LigneCommande constructor.
     */
    public function __construct() {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * @param mixed $commande
     */
    public function setCommande($commande): void
    {
        $this->commande = $commande;
    }

    /**
     * @return mixed
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * @param mixed $produit
     */
    public function setProduit($produit): void
    {
        $this->produit = $produit;
    }

    /**
     * @return mixed
     */
    public function getPrixTot()
    {
        return $this->prix_tot;
    }

    /**
     * @param mixed $prix_tot
     */
    public function setPrixTot($prix_tot): void
    {
        $this->prix_tot = $prix_tot;
    }

}
