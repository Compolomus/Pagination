<?php declare(strict_types=1);

namespace Compolomus\Pagination;

use PHPUnit\Framework\TestCase;


class PaginationTest extends TestCase
{

    public function test__construct(): void
    {
        $nav = new Pagination(1, 10, 20);
        try {
            $this->assertInternalType('object', $nav);
            $this->assertInstanceOf(Pagination::class, $nav);
        } catch (\Exception $e) {
            $this->assertContains('Must be initialized ', $e->getMessage());
        }
    }

    public function testGetOffset(): void
    {
        $nav1 = new Pagination(1, 10, 20);
        $nav2 = new Pagination(2, 5, 30);
        $this->assertEquals($nav1->getOffset(), 0);
        $this->assertEquals($nav2->getOffset(), 5);
    }

    public function testGetLimit(): void
    {
        $nav = new Pagination(1, 10, 20);
        $this->assertEquals($nav->getLimit(), 10);
    }

    public function testGet(): void
    {
        $counts = [11, 11, 13, 13, 13, 13, 13, 13, 19, 20, 21, 21, 20, 19, 18, 17, 16, 15, 14, 13, 11, 11];

        for ($i = 0; $i < 22; $i++) {
            $nav = new Pagination($i, 10, 200, 7);
            $this->assertEquals(count($nav->get()), $counts[$i]);
        }
    }

    public function testGetEnd(): void
    {
        $nav1 = new Pagination(1, 10, 20);
        $nav2 = new Pagination(2, 20, 30);
        $this->assertEquals($nav1->getEnd(), 10);
        $this->assertEquals($nav2->getEnd(), 30);
    }
}
