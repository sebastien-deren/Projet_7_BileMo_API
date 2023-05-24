<?php

namespace App\Service;

use App\DTO\PaginationDto;
use App\Entity\Product;
use Hateoas\Hateoas;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class SerializerService
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function serialize(mixed $data , string $group) :string
    {
        $context = SerializationContext::create()->setGroups(['Default',$group]);
        return $this->serializer->serialize($data,'json',$context);
    }


}
