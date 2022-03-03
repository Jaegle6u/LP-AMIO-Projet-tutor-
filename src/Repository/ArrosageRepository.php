<?php

namespace App\Repository;

use App\Entity\Arrosage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Arrosage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Arrosage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Arrosage[]    findAll()
 * @method Arrosage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArrosageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Arrosage::class);
    }
    public function findAll():array
    {
        $qb = $this->createQueryBuilder('a')
        ->addSelect('plant')
        ->leftJoin('a.plants','p');
        
        return $qb->getQuery()->getResult();
    }
    public function findByID($id)
    {
        $qb = $this->createQueryBuilder('a')
       ->select('a.id,a.minTemperature,
            a.maxTemperature,
            a.minHumidite,
            a.maxHumidite,
            a.minHumiditeSol,
            a.maxHumiditeSol,
            a.checkTemperature,
            a.checkHumidite,
            a.checkHumiditeSol')
        ->where('a.id = :id')
        ->setParameter(":id",$id);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
