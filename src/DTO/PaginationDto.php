<?php

namespace App\DTO;

use Doctrine\Common\Collections\Collection;
use Hateoas\Configuration\Annotation as Hateoas;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\Annotation as Serializer;

class PaginationDto
{

    public function __construct(
        public int $page,
        public int $limit,
        public int $maxPage,
        public array $data,
    )
    {
    }

}
