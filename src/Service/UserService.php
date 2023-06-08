<?php

namespace App\Service;

use App\DTO\PaginationDto;
use App\Entity\Client;
use App\Repository\UserRepository;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use App\Entity\User;




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

    public function create(User $user,Client $client):User
    {
        /*if(!$this->verifyUser($user)){
           throw new BadRequestException("The user you tried to create misses some required information, see the documentation for more detail");
        }*/
        $user->initializeClients()->addClient($client);
        $this->repository->save($user,true);

        return $user;

    }
    private function verifyUser(User $user):bool
    {
        //to be reworked
        if (!($user->getName() && $user->getFirstName() &&  $user->getEmail() &&  $user->getPhoneNumber())){
            return false;
        }
        if(strlen(trim($user->getName()))<3){
            return false;
        }
        if(strlen(trim($user->getFirstName()))<3){
            return false;
        }
        if(strlen($user->getEmail())<3 || !filter_var($user->getEmail(),FILTER_VALIDATE_EMAIL )){
            return false;
        }
        if(strlen($user->getPhoneNumber()<8)){
            return false;
        }
        return true;
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
