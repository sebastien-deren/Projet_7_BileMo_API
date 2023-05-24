<?php

namespace App\Service;

use App\Repository\ClientRepository;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class ClientService
{
    public function __construct(private ClientRepository $repository){}
    public function getValidClient(UserInterface $client,int $id){
        $requestClient = $this->repository->find($id)??throw new RouteNotFoundException();
        if($client->getUserIdentifier() !== $requestClient->getUserIdentifier()){
            return new UnauthorizedHttpException('Bearer realm="example"');
        }
        return $requestClient;
    }

}
