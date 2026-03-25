<?php

namespace App\Resolver;

use App\DTO\UserAggregateQueryDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UserAggregateQueryDTOArgumentResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== UserAggregateQueryDTO::class) {
            return;
        }

        $sort = $request->query->get('sort', 'asc');
        $limit = (int) $request->query->get('limit', 10);
        $cursor = (int) $request->query->get('cursor', 0);
        $country = $request->query->get('country');
        $firstName = $request->query->get('firstName');
        $lastName = $request->query->get('lastName');

        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'asc';
        }
        if ($limit < 1) $limit = 1;
        if ($limit > 100) $limit = 100;
        if ($cursor < 0) $cursor = 0;

        yield new UserAggregateQueryDTO(
            $sort,
            $limit,
            $cursor
        );
    }
}
