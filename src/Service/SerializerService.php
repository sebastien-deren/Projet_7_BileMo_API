<?php

namespace App\Service;

use App\DTO\PaginationDto;
use Hateoas\Hateoas;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class SerializerService
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ){
    }
    public function paginator(string $group, PaginationDto $paginationObject): string
    {

        $context = SerializationContext::create()->setGroups([$group]);

        $list= new PaginatedRepresentation(
            new CollectionRepresentation($paginationObject->list),
            'app_product_list',
            array(),
            $paginationObject->page,
            $paginationObject->limit,
            $paginationObject->maxPage,
        );
        return $this->serializer->serialize($list, 'json');
    }


}
