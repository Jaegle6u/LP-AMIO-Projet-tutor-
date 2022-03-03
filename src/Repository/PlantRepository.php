<?php

namespace App\Repository;

use App\Entity\Plant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Plant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plant[]    findAll()
 * @method Plant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plant::class);
    }
    public function findByID($id):array
    {
        $qb = $this->createQueryBuilder('p')
       
        ->where('p.id = :id')
        ->setParameter(":id",$id);
        return $qb->getQuery()->getResult();
    }

    public function findAllPlanteOfPlantByID($id):array
    {
        $qb = $this->createQueryBuilder('plant')
        ->addSelect('plante')
        ->leftJoin('plant.plantes','plante')
        ->where('plant.id = :id')
        ->setParameter(":id",$id);
        return $qb->getQuery()->getResult();
    }
}
