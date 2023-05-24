<?php

namespace App\Service;

use JMS\Serializer\Context;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class serializerService
{

    public function __construct(private SerializerInterface $serializer){}
    public function serialize(mixed $data,string $group){
        $context = SerializationContext::create();
        $context->setGroups(['Default',$group]);
        return $this->serializer->serialize($data,'json',$context);
    }
}
