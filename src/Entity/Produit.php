<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
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
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false, length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="float", nullable=false, length=255)
     */
    private $prix;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Stocks", mappedBy="produit")
     */
    private $stocks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LigneCommande", mappedBy="produit")
     */
    private $command_lignes;

    /**
     * Produit constructor.
     */
    public function __construct() {
        $this->stocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param mixed $prix
     */
    public function setPrix($prix): void
    {
        $this->prix = $prix;
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
     * @return mixed
     */
    public function getCommandLignes()
    {
        return $this->command_lignes;
    }

    /**
     * @param mixed $command_lignes
     */
    public function setCommandLignes($command_lignes): void
    {
        $this->command_lignes = $command_lignes;
    }

}
