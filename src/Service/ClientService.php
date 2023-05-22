<?php

namespace App\Service;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Response;

class ClientService
{

    public function __construct(
        private ClientRepository $repository,
    ){}

    /***
     * @param Client $client
     * @return array
     */
    public function getPaginateUsers(Client $client,int $page,int $limit):array{
        if ($limit < 0) {
            throw new \Exception("limit must be a positive integer", Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE);
        }
        if ($limit > 1000) {
            throw new \OutOfRangeException("You tried to request too much data", Response::HTTP_REQUEST_ENTITY_TOO_LARGE);
        }
        if ($page < 0) {
            throw new \Exception("page must be a positive integer", Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE);
        }
        $users= $client->getUsers();
        if(($page*$limit)>$users->count()){
            throw new \OutOfRangeException("the data you requested does not exist",Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE);
        }
        $data = $users->slice(($page*$limit)-1,$limit);
        $maxPage= (int) $users->count()/$limit;
        //TO BE CHANGE WHEN OUR PRODUCT BRANCH IS MERGED
        return [$data,$limit,$maxPage,$page];

    }
}
