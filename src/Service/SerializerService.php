<?php

namespace App\Service;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SerializerService
{

    public function __construct(private SerializerInterface $serializer){}
    public function serialize(mixed $data,$group):string{

        $context = SerializationContext::create();
        $context->setGroups(['Default',$group]);
        return $this->serializer->serialize($data,'json',$group);
    }
}
