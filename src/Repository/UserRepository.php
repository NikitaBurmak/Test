<?php

namespace App\Repository;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;

class UserRepository
{
    public function __construct(
        private DocumentManager $dm
    ) {
    }

    public function findUsersWithAggregation(
        string $sort = 'asc',
        int $limit = 10,
        int $cursor = 0
    ): array {
        $builder = $this->dm->createAggregationBuilder(User::class);

        if ($cursor > 0) {
            $builder->match()->field('_id')->gt($cursor);
        }

        $builder->sort('_id', $sort === 'asc' ? 'asc' : 'desc')
            ->limit($limit);

        /** @var User[] $result */
        $result = $builder->execute()->toArray();
        return $result;
    }

    public function countUsers(): int
    {
        $builder = $this->dm->createAggregationBuilder(User::class);
        $builder->count('total');

        $result = $builder->execute()->toArray();
        return $result[0]['total'] ?? 0;
    }
}
