<?php

namespace App\tests\DTO;

use App\DTO\UserAggregateQueryDTO;
use PHPUnit\Framework\TestCase;

class UserAggregateTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $dto = new UserAggregateQueryDTO();

        $this->assertEquals('asc', $dto->sort);
        $this->assertEquals(10, $dto->limit);
        $this->assertEquals(0, $dto->cursor);
    }

    public function testCustomValues(): void
    {
        $dto = new UserAggregateQueryDTO('desc', 20, 5);

        $this->assertEquals('desc', $dto->sort);
        $this->assertEquals(20, $dto->limit);
        $this->assertEquals(5, $dto->cursor);
    }

    public function testSortOrderEnum(): void
    {
        $dtoAsc = new UserAggregateQueryDTO('asc');
        $this->assertEquals('asc', $dtoAsc->sort);

        $dtoDesc = new UserAggregateQueryDTO('desc');
        $this->assertEquals('desc', $dtoDesc->sort);
    }
}
