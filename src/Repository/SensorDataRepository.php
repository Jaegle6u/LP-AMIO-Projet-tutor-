<?php

namespace App\Repository;

use App\Entity\SensorData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SensorData|null find($id, $lockMode = null, $lockVersion = null)
 * @method SensorData|null findOneBy(array $criteria, array $orderBy = null)
 * @method SensorData[]    findAll()
 * @method SensorData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SensorDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SensorData::class);
    }

    public function findLastMesure($id):array
    {
        return $this->createQueryBuilder('s')
           
            
            ->orderBy('s.reading_time', 'DESC')
            ->where('s.sensor = :id')
            ->setParameter(":id",$id)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findAllTempOf($id):array
    {
      
    
      $qb = $this->createQueryBuilder('s')
       ->select('s.value1','s.reading_time')
        ->where('s.sensor = :id')
        ->setParameter(":id",$id);
        return $qb->getQuery()->getResult();
    
    }
    public function findAllHumOf($id):array
    {
      
    
      $qb = $this->createQueryBuilder('s')
       ->select('s.value2','s.reading_time')
        ->where('s.sensor = :id')
        ->setParameter(":id",$id);
        return $qb->getQuery()->getResult();
    
    }
    public function findAllHumSolOf($id):array
    {
      
    
      $qb = $this->createQueryBuilder('s')
       ->select('s.value3','s.reading_time')
        ->where('s.sensor = :id')
        ->setParameter(":id",$id);
        return $qb->getQuery()->getResult();
    
    }
    public function findAllOf($id):array
    {
      
    
      $qb = $this->createQueryBuilder('s')
       
        ->where('s.sensor = :id')
        ->setParameter(":id",$id);
        return $qb->getQuery()->getResult();
    
    }
}
