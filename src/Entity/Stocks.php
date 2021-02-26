<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StocksRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StocksRepository::class)
 * @ApiResource(
 *     normalizationContext={"list"},
 *    collectionOperations={
 *         "get" = { "security" = "is_granted('IS_AUTHENTICATED_ANONYMOUSLY')" },
 *         "post"={"method"="POST", "access_control"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *          "get" = { "security" = "is_granted('IS_AUTHENTICATED_ANONYMOUSLY')" },
 *          "put" = { "security" = "is_granted('ROLE_ADMIN')" },
 *          "delete" = { "security" = "is_granted('ROLE_ADMIN')" }
 *     },
 * )
 */
class Stocks
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit", inversedBy="stocks")
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Magasin", inversedBy="stocks")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $magasin;

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
     * @return Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * @param Produit $produit
     */
    public function setProduit($produit): void
    {
        $this->produit = $produit;
    }

    /**
     * @return Magasin
     */
    public function getMagasin()
    {
        return $this->magasin;
    }

    /**
     * @param Magasin $magasin
     */
    public function setMagasin($magasin): void
    {
        $this->magasin = $magasin;
    }

}
