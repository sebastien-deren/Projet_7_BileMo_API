<?php

namespace App\Service;

use JMS\Serializer\Context;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class SerializerService
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function serializeOnce(object $data , string $group) :string
    {
        $context = SerializationContext::create()->setGroups(['Default',$group]);
        return $this->serializer->serialize($data,'json',$context);
    }
}
