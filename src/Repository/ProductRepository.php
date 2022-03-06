<?php

namespace App\Repository;

use App\Entity\API\APIUser;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

	/**
	 * Get customers of an user and paginate it
	 * @param int $page
	 * @param int $limit
	 * @param string $order
	 * @return Pagerfanta
	 */
	public function getListProducts(int $page = 1, int $limit = 5, string $order = 'ASC'): Pagerfanta
	{

		$queryBuilder = $this->createQueryBuilder('p')
			->orderBy('p.id', $order)
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
	protected function paginate(QueryBuilder $queryBuilder, int $page = 1, int $limit = 5): Pagerfanta
	{

		if (0 == $limit || 0 == $page) {
			throw new \LogicException('"limit" and "page" query parameter must be greater than 0.');
		}

		$pager = new Pagerfanta(new QueryAdapter($queryBuilder));
		$pager->setMaxPerPage((int) $limit);
		$pager->setCurrentPage($page);

		return $pager;
	}
}
