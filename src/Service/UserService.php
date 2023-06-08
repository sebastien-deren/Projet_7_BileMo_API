<?php

namespace App\Service;

use App\DTO\PaginationDto;
use App\Entity\Client;
use App\Repository\UserRepository;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;



class UserService
{
    public function __construct(private UserRepository $repository,private CacheService $cacheService){}
    public function PaginatedListUser(Client $client,int $page, int $limit):PaginationDto
    {

        $cacheName = $this->cacheNameUserList($client->getUserIdentifier(),$page,$limit);
        $dataToGet = function (array $param)  {
            return $this->repository->getPaginateUsers($param['client'],$param['page'],$param['limit']);
        };

        return $this->cacheService->getCachedData($dataToGet, $cacheName, 'userList', ['client'=> $client, 'page'=> $page, 'limit'=> $limit]);
    }
    public function cacheNameUserList(string $client,int $page,int $limit): string
    {
        return  'UserList-Client' . $client . '-page' . $page . "-limit".$limit;
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
