<?php

namespace App\Entity;

use App\Repository\CommandeStatutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeStatutRepository::class)
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
