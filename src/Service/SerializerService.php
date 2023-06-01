<?php declare(strict_types=1);

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
    public function __construct(
        private readonly SerializerInterface $serializer,
    ){
    }
    /**
     * @param string $group
     * @param array<Product> $representation
     * @return string
     */
    public function paginator(string $group,array $representation): string
    {
        $context = SerializationContext::create()->setGroups(['Default',$group]);
        return $this->serializer->serialize($representation,'json',$context);
    }

    public function serializeList(array $productList):string
    {
        return $this->serializer->serialize($productList,'json');
    }


}
