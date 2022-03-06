<?php

namespace App\Repository\API;

use App\Entity\API\APICustomer;
use App\Entity\API\APIUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Hateoas\Configuration\Route;
use Hateoas\Representation\Factory\PagerfantaFactory;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @method APICustomer|null find($id, $lockMode = null, $lockVersion = null)
 * @method APICustomer|null findOneBy(array $criteria, array $orderBy = null)
 * @method APICustomer[]    findAll()
 * @method APICustomer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class APICustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, APICustomer::class);
    }


	/**
	 * Get customers of an user and paginate it
	 * @param APIUser $APIUser
	 * @param $page
	 * @param $limit
	 * @param $order
	 * @return Pagerfanta
	 */
	public function getListCustomers(APIUser $APIUser, $page = 1,$limit = 5, $order = 'ASC') {

		$queryBuilder = $this->createQueryBuilder('c')
			->andWhere('c.apiUser = :user')
			->setParameter('user', $APIUser)
			->orderBy('c.id', $order)
		;

		return $this->paginate($queryBuilder, $page, $limit);
	}

	/**
	 * Function paginate query
	 * @param QueryBuilder $queryBuilder
	 * @param $page
	 * @param $limit
	 * @return Pagerfanta
	 */
	protected function paginate(QueryBuilder $queryBuilder, $page = 1, $limit = 5){

		if (0 == $limit || 0 == $page) {
			throw new \LogicException('"limit" and "page" query parameter must be greater than 0.');
		}

		$pager = new Pagerfanta(new QueryAdapter($queryBuilder));
		$pager->setMaxPerPage((int) $limit);
		$pager->setCurrentPage($page);


		return $pager;
	}
}
