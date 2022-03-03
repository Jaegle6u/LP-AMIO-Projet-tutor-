<?php

namespace App\Repository;

use App\Entity\Rapport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rapport|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rapport|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rapport[]    findAll()
 * @method Rapport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RapportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rapport::class);
    }

    public function findAll():array
    {
        $qb = $this->createQueryBuilder('r')
        ->addSelect('plant')
        ->addSelect('plante')
        ->addSelect('serre')
        ->leftJoin('r.plant','plant')
        ->leftJoin('r.plante','plante')
        ->leftJoin('r.serre','serre');
        
        return $qb->getQuery()->getResult();
    }
    public function findBySerre($id):array
    {
        $qb = $this->createQueryBuilder('r')
        ->addSelect('s')
        ->leftJoin('r.serre','s')
        ->where('s.id = :id')
        ->setParameter(":id",$id);
        return $qb->getQuery()->getResult();
    }
    public function findByPlant($id):array
    {
        $qb = $this->createQueryBuilder('r')
        ->addSelect('p')
        ->leftJoin('r.plant','p')
        ->where('p.id = :id')
        ->setParameter(":id",$id);
        return $qb->getQuery()->getResult();
    }
    public function findByPlante($id):array
    {
        $qb = $this->createQueryBuilder('r')
        ->addSelect('plante')
        ->leftJoin('r.plante','plante')
        ->where('plante.id = :id')
        ->setParameter(":id",$id);
        return $qb->getQuery()->getResult();
    }
}
