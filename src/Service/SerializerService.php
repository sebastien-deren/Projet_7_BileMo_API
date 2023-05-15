<?php declare(strict_types=1);

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

        $context = SerializationContext::create()->setGroups(['Default',$group]);
        $list= new PaginatedRepresentation(
            null,
            'app_product_list',
            [],
            $paginationObject->page,
            $paginationObject->limit,
            $paginationObject->maxPage,
        );
        return $this->serializer->serialize([$paginationObject->products,$list],'json',$context);


    }

    public function serializeList(array $productList):string
    {
        return $this->serializer->serialize($productList,'json');
    }


}
