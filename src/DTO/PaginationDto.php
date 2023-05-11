<?php

namespace App\DTO;

use Doctrine\Common\Collections\Collection;

class PaginationDto
{

    public function __construct(
        public int $page,
        public int $limit,
        public int $maxPage,
        public array $list
    )
    {
    }
}
