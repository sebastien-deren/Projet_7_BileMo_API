<?php

namespace App\Controller;

use App\Entity\Client;
use App\Service\ClientService;
use App\Service\SerializerService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('api/clients/{id}/users',name: 'app_user_list',methods: 'GET')]
    public function list(Client $client, ClientService $service,SerializerService $serializer,UserService $userService, Request $request):JsonResponse
    {
        //might implement that as a voter!
        if($client !== $this->getUser()){
            throw  new AccessDeniedException("access forbidden",Response::HTTP_FORBIDDEN);
        }
        $page= (int)$request->query->get('page',1);
        $limit = (int)$request->query->get('limit',10);
        $jsonResponse= $userService->PaginatedListUserJson($client,$page,$limit);
        return $jsonResponse;
        //$jsonResponse->headers->set($header->paginate($usersPaginated[1,2,3]));
    }

}
