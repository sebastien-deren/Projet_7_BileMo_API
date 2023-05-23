<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('api/clients/{username}/users', name:'app_user_create',methods: 'POST')]
    public function create(Client $client,User $user, UserService $userService,Request $request)
    {
        return  $userService->create($request->getContent(),$this->getUser());
    }

}
