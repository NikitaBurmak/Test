<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    /**
     * @return User[]
     */
    public function findUsersWithCursor(string $sort = 'asc', int $limit = 10, int $cursor = 0): array
    {
        $qb = $this->createQueryBuilder('u');

        if ($cursor > 0) {
            $qb->andWhere($sort === 'asc' ? 'u.id > :cursor' : 'u.id < :cursor')
                ->setParameter('cursor', $cursor);
        }

        $qb->orderBy('u.id', $sort === 'asc' ? 'ASC' : 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
