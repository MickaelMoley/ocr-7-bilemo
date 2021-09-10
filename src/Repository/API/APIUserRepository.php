<?php

namespace App\Repository\API;

use App\Entity\API\APIUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method APIUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method APIUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method APIUser[]    findAll()
 * @method APIUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class APIUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, APIUser::class);
    }

    // /**
    //  * @return APIUser[] Returns an array of APIUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?APIUser
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
