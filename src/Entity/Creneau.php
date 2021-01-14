<?php

namespace App\Entity;

use App\Repository\CreneauRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CreneauRepository::class)
 */
class Creneau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id_creneau", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @ORM\Column(name="reserver", type="boolean")
     */
    private $reserver;

    /**
     * @ORM\OneToOne(targetEntity="Commande", mappedBy="creneau")
     */
    private $commande;

    /**
     * @var Magasin
     *
     * @ORM\ManyToOne(targetEntity="Magasin", inversedBy="creneaus")
     * @ORM\JoinColumn(name="id_magasin", referencedColumnName="id")
     */
    private $magasin;


    public function getId(): ?int
    {
        return $this->id;
    }
}
