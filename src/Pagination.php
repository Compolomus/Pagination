<?php declare(strict_types=1);

namespace Compolomus\Pagination;

class Pagination
{
    private $total;

    private $page;

    private $limit;

    private $totalPages;

    private $length;

    /**
     * Pagination constructor.
     * @param int $page
     * @param int $limit
     * @param int $total
     * @param int $length
     */
    public function __construct(int $page, int $limit, int $total, int $length = 3)
    {
        $this->totalPages = (int)ceil($total / $limit);
        $this->page = $page < 1 ? 1 : ($page > $this->totalPages ? $this->totalPages : $page);
        $this->limit = $limit > 0 ? $limit : 10;
        $this->total = $total;
        $this->length = $length >= 0 ? $length : 3;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getEnd(): int
    {
        return $this->page === $this->totalPages ? $this->total : $this->page * $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->page === 1 ? 0 : ($this->page - 1) * $this->limit;
    }

    /**
     * @return array
     */
    private function leftPad(): array
    {
        $result = [];

        $leftDots = $this->page - $this->length;

        if ($leftDots > 0) {
            $result[2] = '...';
        }

        foreach ($leftDots > 0 ? range($this->page, $leftDots) : range($this->page, 2) as $value) {
            $result[$value] = $value;
        }

        return $result;
    }

    /**
     * @return array
     */
    private function rightPad(): array
    {
        $result = [];

        $rightDots = ($this->totalPages - 1) - ($this->page + $this->length);

        foreach ($rightDots > 0 ? range($this->page, $this->page + $this->length) : range($this->page, $this->totalPages - 1) as $value) {
            $result[$value] = $value;
        }

        if ($rightDots > 0) {
            $result[$this->totalPages - 1] = '...';
        }

        return $result;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $result = [1 => 'first'];

        $result += $this->leftPad();

        $result[$this->page] = 'current';

        $result += $this->rightPad();

        $result[$this->totalPages] = 'last';

        ksort($result);

        return $result;
    }
}
