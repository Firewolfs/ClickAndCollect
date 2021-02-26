<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CreneauRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CreneauRepository::class)
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
class Creneau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id_creneau", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="date", type="datetime", nullable=false)
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
     * @ORM\ManyToOne(targetEntity="Magasin", inversedBy="creneaux")
     * @ORM\JoinColumn(name="id_magasin", referencedColumnName="id", onDelete="CASCADE")
     */
    private $magasin;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getReserver()
    {
        return $this->reserver;
    }

    /**
     * @param mixed $reserver
     */
    public function setReserver($reserver): void
    {
        $this->reserver = $reserver;
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
     * @return Magasin
     */
    public function getMagasin(): Magasin
    {
        return $this->magasin;
    }

    /**
     * @param Magasin $magasin
     */
    public function setMagasin(Magasin $magasin): void
    {
        $this->magasin = $magasin;
    }

}
