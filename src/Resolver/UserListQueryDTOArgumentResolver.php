<?php

namespace App\Resolver;

use App\DTO\UserListQueryDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validation;

class UserListQueryDTOArgumentResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== UserListQueryDTO::class) {
            return;
        }

        $sort = $request->query->get('sort', 'asc');
        $limit = (int) $request->query->get('limit', 10);
        $cursor = (int) $request->query->get('cursor', 0);

        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'asc';
        }
        if ($limit < 1) $limit = 1;
        if ($limit > 100) $limit = 100;
        if ($cursor < 0) $cursor = 0;

        yield new UserListQueryDTO($sort, $limit, $cursor);
    }
}
