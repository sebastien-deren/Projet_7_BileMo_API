<?php declare(strict_types=1);

namespace App\Service;

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
        return $this->serializer->serialize($data,'json',$context);
    }
    public function deserialize(string $data,string $type,string $format):mixed
    {
        return $this->serializer->deserialize($data,$type,$format);
    }


}
