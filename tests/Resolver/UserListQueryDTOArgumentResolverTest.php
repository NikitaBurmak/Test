<?php

namespace App\Tests\Resolver;

use App\DTO\UserListQueryDTO;
use App\Resolver\UserListQueryDTOArgumentResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UserListQueryDTOArgumentResolverTest extends TestCase
{
    private UserListQueryDTOArgumentResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new UserListQueryDTOArgumentResolver();
    }

    public function testResolveReturnsDTOWithDefaultValues(): void
    {
        $request = Request::create('/users');
        $argument = new ArgumentMetadata('query', UserListQueryDTO::class, false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertCount(1, $result);
        $this->assertInstanceOf(UserListQueryDTO::class, $result[0]);
        $this->assertEquals('asc', $result[0]->sort);
        $this->assertEquals(10, $result[0]->limit);
        $this->assertEquals(0, $result[0]->cursor);
    }

    public function testResolveWithCustomQueryParams(): void
    {
        $request = Request::create('/users?sort=desc&limit=25&cursor=100');
        $argument = new ArgumentMetadata('query', UserListQueryDTO::class, false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertCount(1, $result);
        $this->assertEquals('desc', $result[0]->sort);
        $this->assertEquals(25, $result[0]->limit);
        $this->assertEquals(100, $result[0]->cursor);
    }

    public function testResolveInvalidSortDefaultsToAsc(): void
    {
        $request = Request::create('/users?sort=invalid');
        $argument = new ArgumentMetadata('query', UserListQueryDTO::class, false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertEquals('asc', $result[0]->sort);
    }

    public function testResolveLimitBoundedToMax100(): void
    {
        $request = Request::create('/users?limit=200');
        $argument = new ArgumentMetadata('query', UserListQueryDTO::class, false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertEquals(100, $result[0]->limit);
    }

    public function testResolveLimitBoundedToMin1(): void
    {
        $request = Request::create('/users?limit=0');
        $argument = new ArgumentMetadata('query', UserListQueryDTO::class, false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertEquals(1, $result[0]->limit);
    }

    public function testResolveCursorDefaultsToZero(): void
    {
        $request = Request::create('/users?cursor=-5');
        $argument = new ArgumentMetadata('query', UserListQueryDTO::class, false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertEquals(0, $result[0]->cursor);
    }

    public function testResolveReturnsEmptyWhenWrongType(): void
    {
        $request = Request::create('/users');
        $argument = new ArgumentMetadata('query', 'WrongClass', false, false, null);

        $result = iterator_to_array($this->resolver->resolve($request, $argument));

        $this->assertCount(0, $result);
    }
}
