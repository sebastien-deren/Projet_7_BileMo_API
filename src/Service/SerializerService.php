<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use Hateoas\Hateoas;
use Hateoas\HateoasBuilder;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Config\JmsSerializer\DefaultContext\SerializationConfig;

class SerializerService
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ){
    }

    /**
     * @param string $group
     * @param mixed $data
     * @return string
     */
    public function serialize(string $group,mixed $data):string
    {
        $context = SerializationContext::create()->setGroups(['Default',$group]);
        $context->getCurrentPath();
        $hateoas = HateoasBuilder::create()->setExpressionContextVariable('client','green')->build();
        $context->setAttribute('client',9);
        return $this->serializer->serialize($data,'json',$context);
    }


}
