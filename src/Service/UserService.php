<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use App\DTO\PaginationDto;


class UserService
{
    public function __construct(
        private UserRepository $repository,
        private CacheService   $cacheService,
    )
    {
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

    public function cacheNameDetail(int $userId): string
    {
        return 'userDetails' . $userId;
    }

    public function paginatedListUser(Client $client, int $page, int $limit): PaginationDto
    {

        $cacheName = $this->cacheNameUserList($client->getUserIdentifier(), $page, $limit);
        $dataToGet = function (array $param) {
            return $this->repository->getPaginateUsers($param['client'], $param['page'], $param['limit']);
        };
        return $this->cacheService->getCachedData($dataToGet, $cacheName, 'userList', ['client' => $client, 'page' => $page, 'limit' => $limit]);
    }

    public function cacheNameUserList(string $client, int $page, int $limit): string
    {
        return 'UserList-Client' . $client . '-page' . $page . "-limit" . $limit;
    }

    public function delete(User $user,Client $client):User|false
    {
        if (!$client->getUsers()->contains($user)) {
            return false;
        }
        if (1 === $user->getClients()->count()) {
            $this->repository->remove($user, true);
            return $user;
        }
        $user->removeClient($client);
        $this->repository->save($user, true);
        return $user;
    }

}
