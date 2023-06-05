<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ){}
    public function delete(User $user,Client $client):User|false
    {
        if (!$client->getUsers()->contains($user)) {
            return false;
        }
        if (1 === $user->getClients()->count()) {
            $this->userRepository->remove($user, true);
            return $user;
        }
        $user->removeClient($client);
        $this->userRepository->save($user, true);
        return $user;
    }

}
