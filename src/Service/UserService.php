<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use Container934xOsd\getSerializerService;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class UserService
{
    public function __construct(private UserRepository $repository,private TagAwareCacheInterface $cache, private serializerService $serializerService)
    {}
    public function findOneValid(int $id,int $clientId)
    {
        return $this->repository->find($id) ?? throw new RouteNotFoundException();
    }
    public function getValidUser(int $id, Client $client):User
    {
        $user = $this->repository->find($id)?? throw new RouteNotFoundException();
        $clients = $user->getClients();
        $isClient = $clients->exists(function($key,$value)use ($client){
            return  $value ===  $client;
        });
        return $isClient ? $user : throw new AccessDeniedException("you don't have the right to access this client",Response::HTTP_FORBIDDEN);


    }
    public function detailJsonResponse(User $user):JsonResponse
    {
        return $this->cache->get('userDetail-'.$user->getId(),
        function(ItemInterface $item) use ($user){
            $item->tag($user->getId());
            $item->expiresAfter(3600);
            return new JsonResponse($this->serializerService->serialize($user,'UserDetail'),Response::HTTP_OK,[],true);
        });

    }
}
