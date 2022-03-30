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

    private bool $uiKeys;

    /**
     * @param int $page
     * @param int $limit
     * @param int $total
     * @param int $length
     * @param bool $uiKeys
     */
    public function __construct(int $page, int $limit, int $total, int $length = 3, bool $uiKeys = false)
    {
        $this->totalPages = (int) ceil($total / $limit);
        $this->page = $page > 1 ? ($page > $this->totalPages ? 1 : $page) : 1;
        $this->limit = $limit > 0 ? $limit : 10;
        $this->total = $total;
        $this->length = $length >= 0 ? $length : 3;
        $this->pos = $this->init();
        $this->uiKeys = $uiKeys;
    }

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

        if ($this->uiKeys) {
            if ($this->page > 1) {
                $result['prev'] = $this->page - 1;
            }
            if ($this->page !== 1) {
                $result['first'] = 1;
            }
            if ($this->page > 3) {
                $result['second'] = 2;
            }
        }

        return $this->pos !== 'full' ? $result : [];
    }

    /**
     * @return array
     */
    private function end(): array
    {
        $result = [];

        if ($this->uiKeys) {
            if ($this->page !== $this->totalPages) {
                $result['last'] = $this->totalPages;
            }
            if ($this->totalPages - $this->page > 0) {
                $result['next'] = $this->page + 1;
            }
            if ($this->totalPages - $this->page + $this->length > 3) {
                $result['preLast'] = $this->totalPages - 1;
            }
        }

        return $this->pos !== 'full' ? $result : [];
    }

    /**
     * @return array
     */
    private function leftDots(): array
    {
        $result = [];

        if ($this->pos !== 'noLeftDots') {
            if ($this->uiKeys) {
                $result['leftDots'] = '...';
            } else {
                $result[] = '...';
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    private function leftPad(): array
    {
        $result = [];
        $for = [];

            if ($this->page - $this->length > 1) {
                $result[] = 1;
            }

            foreach (range($this->page - 1, $this->page - $this->length) as $value) {
                if ($value > 0) {
                    $for[] = $value;
                }
            }

        return $this->pos === 'full' ? [] : array_merge($result, $this->leftDots(), array_reverse($for));
    }

    private function rightDots(): array
    {
        $result = [];

        if ($this->pos !== 'noRightDots') {
            if ($this->uiKeys) {
                $result['rightDots'] = '...';
            } else {
                $result[] = '...';
            }
        }

        return $result;
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

        $last = ($this->totalPages - $this->page - $this->length > 0) ? [$this->totalPages] : [];

        return $this->pos === 'full' ? [] : array_merge($result, $this->rightDots(), $last);
    }

    /**
     * @return array
     */
    private function getCurrent(): array
    {
        if ($this->uiKeys) {
            $result['current'] = $this->page;
        } else {
            $result[] = $this->page;
        }

        if ($this->pos === 'full') {
            $result = [];
            foreach (range(1, $this->totalPages) as $value) {
                if ($this->uiKeys && $value === $this->page) {
                    $result['current'] = $value;
                } else {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return array_merge($this->start(), $this->leftPad(), $this->getCurrent(), $this->rightPad(), $this->end());
    }

    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }
}
