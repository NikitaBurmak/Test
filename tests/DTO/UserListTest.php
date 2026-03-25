<?php

namespace App\tests\DTO;

use App\DTO\UserListQueryDTO;
use PHPUnit\Framework\TestCase;

class UserListTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $dto = new UserListQueryDTO();

        $this->assertEquals('asc', $dto->sort);
        $this->assertEquals(10, $dto->limit);
        $this->assertEquals(0, $dto->cursor);
    }

    public function testCustomValues(): void
    {
        $dto = new UserListQueryDTO('desc', 20, 5);

        $this->assertEquals('desc', $dto->sort);
        $this->assertEquals(20, $dto->limit);
        $this->assertEquals(5, $dto->cursor);
    }

    public function testSortOrderEnum(): void
    {
        $dtoAsc = new UserListQueryDTO('asc');
        $this->assertEquals('asc', $dtoAsc->sort);

        $dtoDesc = new UserListQueryDTO('desc');
        $this->assertEquals('desc', $dtoDesc->sort);
    }
}
