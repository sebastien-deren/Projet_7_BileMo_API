<?php

namespace App\Service;

use App\Entity\Client;
use App\Repository\UserRepository;
use App\Service\Headers\PaginationHeaderInterface;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class UserService
{
    public function __construct(private UserRepository $repository,private SerializerInterface $serializer,private CacheService $cacheService, private PaginationHeaderInterface $paginationHeader){}
    public function PaginatedListUserJson(Client $client,int $page, int $limit):JsonResponse
    {

        $cacheName = 'UserList-Client' . $client->getUserIdentifier() . '-page' . $page . "-limit";
        $dataToGet = function (array $param)  {
            $userList = $this->repository->getPaginateUsers($param['client'],$param['page'],$param['limit']);;
            $response = new JsonResponse($this->serializer->serialize( $userList->data,"userList"), Response::HTTP_OK, [], true);
            $this->paginationHeader->setHeaders($response, $userList, 'app_user_list');
            return $response;
        };
        return $this->cacheService->getCachedData($dataToGet, $cacheName, 'userList', ['client'=> $client, 'page'=> $page, 'limit'=> $limit]);

    }


}
