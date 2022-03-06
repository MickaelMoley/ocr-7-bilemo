<?php

namespace App\Repository\API;

use App\Entity\API\APIUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

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


}
