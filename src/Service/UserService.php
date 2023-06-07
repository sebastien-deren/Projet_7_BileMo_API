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
    public function __construct(
        private UserRepository    $repository,
        private CacheService      $cacheService,
    )
    {
    }

    public function findOneValid(int $id, int $clientId)
    {
        return $this->repository->find($id) ?? throw new RouteNotFoundException();
    }

    public function getValidUser(int $id, Client $client): User
    {
        $dataToGet = function ($param) {
            $user = $this->repository->find($param['id']) ?? throw new RouteNotFoundException();
            return $user->getClients()->contains($param['client']) ?
                $user :
                throw new AccessDeniedException(
                    "you don't have the right to access this client",
                    Response::HTTP_FORBIDDEN
                );
        };

        return $this->cacheService->getCachedData(
            $dataToGet,
            $this->cacheNameDetail($id),
            'userDetail' . $id,
            ['id' => $id, 'client' => $client]);

    }
        public function cacheNameDetail($userId)
        {
            return 'userDetails'.$userId;
        }


}
