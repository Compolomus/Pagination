<?php

declare(strict_types=1);

namespace Compolomus\Pagination;

class Pagination
{
    private int $total;

    private int $page;

    private int $limit;

    private int $totalPages;

    private int $length;

    private string $pos;

    /**
     * @param int $page
     * @param int $limit
     * @param int $total
     * @param int $length
     */
    public function __construct(int $page, int $limit, int $total, int $length = 3)
    {
        $this->totalPages = (int) ceil($total / $limit);
        $this->page = $page > 1 ? ($page > $this->totalPages ? 1 : $page) : 1;
        $this->limit = $limit > 0 ? $limit : 10;
        $this->total = $total;
        $this->length = $length >= 0 ? $length : 3;
        $this->pos = $this->init();
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
    public function get(): array
    {
        return array_merge($this->leftPad(), $this->getCurrent(), $this->rightPad());
    }

    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * @return int|null
     */
    public function getNextPage(): ?int
    {
        return $this->totalPages - $this->page > 0 ? $this->page + 1 : null;
    }

    /**
     * @return int|null
     */
    public function getPreviousPage(): ?int
    {
        return $this->page > 1 ? $this->page - 1 : null;
    }

    /**
     * @return string
     */
    private function init(): string
    {
        switch ($this->page) {
            case ($this->totalPages < 7):
            default:
                $pos = 'full';
                break;
            case (($this->page - $this->length) < 3):
                $pos = 'noLeftDots';
                break;
            case (($this->page - $this->length) >= 3 && ($this->totalPages - $this->page - $this->length) > 1):
                $pos = 'center';
                break;
            case (abs($this->totalPages - $this->page - $this->length) >= 0):
                $pos = 'noRightDots';
                break;
        }

        return $pos;
    }

    /**
     * @return array
     */
    private function leftDots(): array
    {
        return $this->pos !== 'noLeftDots' ? ['...'] : [];
    }

    /**
     * @return array
     */
    private function leftPad(): array
    {
        $result = [];

        foreach (range($this->page - 1, $this->page - $this->length) as $value) {
            if ($value > 0) {
                $result[] = $value;
            }
        }

        return $this->pos === 'full' ? [] : array_merge(
            ($this->getFirstPage() ? [$this->getFirstPage()] : []),
            $this->leftDots(),
            array_reverse($result)
        );
    }

    /**
     * @return array
     */
    private function rightDots(): array
    {
        return $this->pos !== 'noRightDots' ? ['...'] : [];
    }

    /**
     * @return array
     */
    private function rightPad(): array
    {
        $result = [];

        foreach (range($this->page + 1, $this->page + $this->length) as $value) {
            if ($value <= $this->totalPages) {
                $result[] = $value;
            }
        }

        return $this->pos === 'full' ? [] : array_merge(
            $result,
            $this->rightDots(),
            ($this->getLastPage() ? [$this->getLastPage()] : [])
        );
    }

    /**
     * @return array
     */
    private function getCurrent(): array
    {
        $result[] = $this->page;

        if ($this->pos === 'full') {
            $result = range(1, $this->totalPages);
        }

        return $result;
    }

    /**
     * @return int|null
     */
    private function getFirstPage(): ?int
    {
        return $this->page - $this->length > 1 ? 1 : null;
    }

    /**
     * @return int|null
     */

    private function getLastPage(): ?int
    {
        return $this->totalPages - $this->page - $this->length > 0 ? $this->totalPages : null;
    }
}
