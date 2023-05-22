<?php

namespace App\Service;

use App\Entity\Client;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class UserService
{
    public function __construct(private ClientService $service,private SerializerInterface $serializer,private TagAwareCacheInterface $cache){}
    public function PaginatedListUserJson(Client $client,int $page, int $limit):JsonResponse{


       return $this->cache->get('UserList-Client'.$client->getUserIdentifier().'-page'.$page."-limit".$limit,function(ItemInterface $item) use ($client,$page,$limit){
            $item->tag('client'.$client->getUserIdentifier());
            $item->expiresAfter(3600);
            $usersPaginated = $this->service->getPaginateUsers($client,$page,$limit);
            return new JsonResponse($this->serializer->serialize($usersPaginated[0],'UserList'),Response::HTTP_OK,[],'json');
        });
    }

}
