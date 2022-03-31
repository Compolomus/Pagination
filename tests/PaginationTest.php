<?php
declare(strict_types=1);

namespace Compolomus\Pagination;

use PHPUnit\Framework\TestCase;


class PaginationTest extends TestCase
{
    public function test__construct(): void
    {
        $nav = new Pagination(1, 10, 20);
        try {
            $this->assertIsObject($nav);
            $this->assertInstanceOf(Pagination::class, $nav);
        } catch (\Exception $e) {
            $this->assertStringContainsString('Must be initialized ', $e->getMessage());
        }
    }

    public function testGetOffset(): void
    {
        $nav1 = new Pagination(1, 10, 20);
        $nav2 = new Pagination(2, 5, 30);
        $this->assertEquals(0, $nav1->getOffset());
        $this->assertEquals(5, $nav2->getOffset());
    }

    public function testGetLimit(): void
    {
        $nav = new Pagination(1, 10, 20);
        $this->assertEquals(10, $nav->getLimit());
    }

    public function testGet(): void
    {
        $results = [
            [1, 2, 3, 4, 5, 6, 7, 8, '...', 20],
            [1, 2, 3, 4, 5, 6, 7, 8, '...', 20],
            [1, 2, 3, 4, 5, 6, 7, 8, 9, '...', 20],
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, '...', 20],
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, '...', 20],
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, '...', 20],
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, '...', 20],
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, '...', 20],
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, '...', 20],
            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, '...', 20],
            [1, '...', 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, '...', 20],
            [1, '...', 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, '...', 20],
            [1, '...', 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            [1, '...', 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            [1, '...', 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            [1, '...', 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            [1, '...', 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            [1, '...', 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            [1, '...', 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            [1, '...', 12, 13, 14, 15, 16, 17, 18, 19, 20],
            [1, '...', 13, 14, 15, 16, 17, 18, 19, 20],
            [1, 2, 3, 4, 5, 6, 7, 8, '...', 20]
        ];

        for ($i = 0; $i < 22; $i++) {
            $nav = new Pagination($i, 10, 200, 7);
            $this->assertEquals($nav->get(), $results[$i]);
        }
    }

    public function testGetEnd(): void
    {
        $nav1 = new Pagination(1, 10, 20);
        $nav2 = new Pagination(2, 20, 30);
        $this->assertEquals(10, $nav1->getEnd());
        $this->assertEquals(30, $nav2->getEnd());
    }

    public function testGetTotalPages(): void
    {
        $nav = new Pagination(1, 10, 200);
        $this->assertEquals(20, $nav->getTotalPages());
    }

    public function testGetNextPage(): void
    {
        $nav = new Pagination(1, 10, 200);
        $nav2 = new Pagination(10, 10, 100);
        $this->assertEquals(2, $nav->getNextPage());
        $this->assertNull($nav2->getNextPage());
    }

    public function testGetPreviousPage(): void
    {
        $nav = new Pagination(3, 10, 200);
        $nav2 = new Pagination(1, 10, 100);
        $this->assertEquals(2, $nav->getPreviousPage());
        $this->assertNull($nav2->getPreviousPage());
    }
}
