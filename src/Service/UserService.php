<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\UserRepository;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $repository,
    )
    {
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

}
