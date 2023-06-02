<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
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
     * @param mixed $data
     * @return string
     */
    public function serialize(string $group,mixed $data):string
    {
        $context = SerializationContext::create()->setGroups(['Default',$group]);
        return $this->serializer->serialize($data,'json',$context);
    }


}
