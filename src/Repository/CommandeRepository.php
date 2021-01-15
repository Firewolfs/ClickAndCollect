<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function findCommandeEnCours($client){
        $qb = $this->createQueryBuilder('c')
            ->join('c.etat', 'e')
            ->andWhere('e.id < 4')
            ->andWhere('c.client = :clientId')
            ->setParameter('clientId', $client)
            ->orderBy('c.etat', 'DESC')
        ;
        return $qb->getQuery()->getResult();
    }

    public function findCommandeTerminer($client){
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.etat', 'e')
            ->andWhere('e.id > 3')
            ->andWhere('c.client = :clientId')
            ->setParameter('clientId', $client)
            ->orderBy('c.etat', 'DESC')

        ;
        return $qb->getQuery()->getResult();
    }
}
