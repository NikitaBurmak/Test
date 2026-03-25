<?php

namespace App\Tests\Resolver;

use App\DTO\UserAggregateQueryDTO;
use App\Resolver\UserAggregateQueryDTOArgumentResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UserAggregateQueryDTOArgumentResolverTest extends TestCase
{
    private UserAggregateQueryDTOArgumentResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new UserAggregateQueryDTOArgumentResolver();
    }

    public function testResolveReturnsDTOWithDefaultValues(): void
    {
        $request = Request::create('/users');
        $argument = new ArgumentMetadata('query', UserAggregateQueryDTO::class, false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertCount(1, $result);
        $this->assertInstanceOf(UserAggregateQueryDTO::class, $result[0]);
        $this->assertEquals('asc', $result[0]->sort);
        $this->assertEquals(10, $result[0]->limit);
        $this->assertEquals(0, $result[0]->cursor);
    }

    public function testResolveWithCustomParams(): void
    {
        $request = Request::create('/users?sort=desc&limit=50&cursor=200');
        $argument = new ArgumentMetadata('query', UserAggregateQueryDTO::class, false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertCount(1, $result);
        $this->assertEquals('desc', $result[0]->sort);
        $this->assertEquals(50, $result[0]->limit);
        $this->assertEquals(200, $result[0]->cursor);
    }

    public function testResolveReturnsEmptyWhenWrongType(): void
    {
        $request = Request::create('/users');
        $argument = new ArgumentMetadata('query', 'WrongClass', false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertCount(0, $result);
    }
}
