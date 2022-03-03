<?php

namespace App\Repository;

use App\Entity\Serre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Serre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serre[]    findAll()
 * @method Serre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serre::class);
    }

    public function findAll():array
    {
        $qb = $this->createQueryBuilder('s')
        ->addSelect('p')
        ->leftJoin('s.plants','p');
        
        return $qb->getQuery()->getResult();
    }
    public function findByID($id):array
    {
        $qb = $this->createQueryBuilder('s')
        ->addSelect('p')
        ->leftJoin('s.plants','p')
        ->where('s.id = :id')
        ->setParameter(":id",$id);
        return $qb->getQuery()->getResult();
    }
}
