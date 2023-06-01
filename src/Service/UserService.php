<?php

namespace App\Service;

use App\DTO\PaginationDto;
use App\Entity\Client;
use App\Repository\UserRepository;
use App\Service\Headers\PaginationHeaderInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class UserService
{
    public function __construct(private UserRepository $repository,private CacheService $cacheService){}
    public function PaginatedListUserJson(Client $client,int $page, int $limit):PaginationDto
    {

        $cacheName = $this->cacheNameUserList($client->getUserIdentifier(),$page,$limit);
        $dataToGet = function (array $param)  {
            return $this->repository->getPaginateUsers($param['client'],$param['page'],$param['limit']);;
        };
        return $this->cacheService->getCachedData($dataToGet, $cacheName, 'userList', ['client'=> $client, 'page'=> $page, 'limit'=> $limit]);

    }
    public function cacheNameUserList(string $client,int $page,int $limit): string
    {
        return  'UserList-Client' . $client . '-page' . $page . "-limit".$limit;
    }


}
