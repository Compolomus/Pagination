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
     *
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
    private function start(): array
    {
        $result = [];

        if ($this->page > 1) {
            $result['minus'] = $this->page - 1;
        }

        if ($this->page !== 1) {
            $result['first'] = 1;
        }

        return $result;
    }

    /**
     * @return int
     */
    private function leftDots(): int
    {
        return $this->page - $this->length;
    }

    /**
     * @return array
     */
    private function leftPad(): array
    {
        $result = [];

        $leftDots = $this->leftDots();

        if ($leftDots > 2) {
            $result['leftDots'] = '...';
        }

        if ($leftDots > 0) {
            foreach (array_reverse(range($this->page - 1, $leftDots)) as $value) {
                $result[$value] = $value;
            }
            if (isset($result[1])) {
                unset($result[1]);
            }
        }

        return $result;
    }

    /**
     * @return int
     */
    private function rightDots(): int
    {
        return ($this->totalPages - 1) - ($this->page + $this->length);
    }

    /**
     * @return array
     */
    private function rightPad(): array
    {
        $result = [];

        $rightDots = $this->rightDots();

        $array = $rightDots > 0
            ? range($this->page + 1, $this->page + $this->length)
            : ($this->page + 1 < $this->totalPages
                ? range($this->page + 1, $this->totalPages - 1)
                : []
            );

        foreach ($array as $value) {
            $result[$value] = $value;
        }

        if ($rightDots > 0) {
            $result['rightDots'] = '...';
        }

        return $result;
    }

    /**
     * @return array
     */
    private function end(): array
    {
        $result = [];

        if ($this->page !== $this->totalPages) {
            $result['last'] = $this->totalPages;
        }

        if ($this->totalPages - $this->page > 0) {
            $result['plus'] = $this->page + 1;
        }

        return $result;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return array_merge($this->start(), $this->leftPad(), ['current' => $this->page], $this->rightPad(), $this->end());
    }
}
