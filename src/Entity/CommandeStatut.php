<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommandeStatutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeStatutRepository::class)
 * @ApiResource(
 *     normalizationContext={"list"},
 *     itemOperations={
 *          "get" = { "security" = "is_granted('ROLE_USER')" },
 *          "put" = { "security" = "is_granted('ROLE_ADMIN')" },
 *          "delete" = { "security" = "is_granted('ROLE_ADMIN')" }
 *     },
 * )
 */
class CommandeStatut
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
     * @ORM\OneToMany(targetEntity="App\Entity\Commande", mappedBy="etat")
     */
    private $commandes;

    /**
     * CommandeStatut constructor.
     */
    public function __construct() {
        $this->commandes = new ArrayCollection();
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
     */
    public function getCommandes()
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
