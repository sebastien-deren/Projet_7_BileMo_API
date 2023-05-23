<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/api/clients/{username}/users/{id}', name: 'app_user_delete', methods: 'DELETE')]
    public function delete(Client $client, User $user,UserService $service)
    {
        if($client !== $this->getUser()){
            throw new UnauthorizedHttpException('bearer token ');
        }
        $service->delete($user,$client);
        return new JsonResponse('user has been deleted',Response::HTTP_NO_CONTENT,[],true);
    }

}
