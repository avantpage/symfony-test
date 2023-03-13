<?php

namespace App\Model\Repository;

use Doctrine\ORM\AbstractQuery;
use App\Model\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends EntityRepository
{
	public function __construct(EntityManagerInterface $entityManager)
	{
		$class = $entityManager->getClassMetadata(User::class);
		parent::__construct($entityManager, $class);
	}

	public function getList(array $filter): array
	{
		$q = $this->createQueryBuilder('u');
		$q
			->select('u')
			->orderBy("u.firstName", "ASC");
		if (!empty($filter['first_name'])) {
			$q->andWhere($q->expr()->eq('u.firstName', ':firstName'))
				->setParameter('firstName', $filter['first_name']);
		}
		if (!empty($filter['last_name'])) {
			$q->andWhere($q->expr()->eq('u.lastName', ':lastName'))
				->setParameter('lastName', $filter['last_name']);
		}
		if (!empty($filter['email'])) {
			$q->andWhere($q->expr()->eq('u.email', ':email'))
				->setParameter('email', $filter['email']);
		}
		return $q->getQuery()->getResult(AbstractQuery::HYDRATE_OBJECT);
	}
}
