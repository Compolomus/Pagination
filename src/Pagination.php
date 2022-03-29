<?php declare(strict_types=1);

namespace Compolomus\Pagination;

class Pagination
{
    private $total;

    private $page;

    private $limit;

    private $totalPages;

    private $length;
    
    private $pos;

    /**
     * Pagination constructor.
     *
     * @param int $page
     * @param int $limit
     * @param int $total
     * @param int $length
     */
    public function __construct(int $page, int $limit, int $total, int $length = 3, bool $uiKeys = true)
    {
        $this->totalPages = (int)ceil($total / $limit);
        $this->page = $page > 1 ? ($page > $this->totalPages ? 1 : $page) : 1;
        $this->limit = $limit > 0 ? $limit : 10;
        $this->total = $total;
        $this->length = $length >= 0 ? $length : 3;
        $this->pos = $this->init();
        $this->uiKeys = $uiKeys;
    }
    
    private function init(): string
    {
		$pos = 'full';
		
		switch ($this->page) {
			case (($this->page - $this->length) < 3):
				$pos  = 'noLeftDots';
				break;
			case (($this->page - $this->length) >= 3 && ($this->totalPages - $this->page - $this->length) > 1):
				$pos  = 'center';
				break;
			case (abs($this->totalPages - $this->page - $this->length) >= 0):
				$pos  = 'noRightDots';
				break;
			case ($this->totalPages < 7):
				$pos = 'full';
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

        if ($this->page > 1) {
            $result['prev'] = $this->page - 1;
        }
        
        if ($this->page !== 1) {
            $result['first'] = 1;
        }
        
        if ($this->page > 3) {
			$result['second'] = 2;
		}

        return $this->uiKeys ? $result : [];
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
            $result['next'] = $this->page + 1;
        }
        
        if ($this->totalPages - $this->page + $this->length > 3) {
			$result['preLast'] = $this->totalPages - 1;
        }

        return $this->uiKeys ? $result : [];
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

        if ($this->pos !== 'noLeftDots') {
			if ($this->uiKeys) {
				$result['leftDots'] = '...';
			} else {
				$result[] = '...';
			}
        }
		
		foreach (range($this->page - 1, $this->page - $this->length) as $value) {
            if ($value > 0) {
				$for[] = $value;
            }
        }

        return array_merge($result, array_reverse($for));
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

        if ($this->pos !== 'noRightDots') {
			if ($this->uiKeys) {
				$result['rightDots'] = '...';
			} else {
				$result[] = '...';
			}
        }
        
        if ($this->totalPages - $this->page - $this->length > 0) {
			$result[] = $this->totalPages;
        }

        return $result;
    }
    
    private function getCurrent(): array
    {
		if ($this->uiKeys) {
				$result['current'] = $this->page;
			} else {
				$result[] = $this->page;
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
}
