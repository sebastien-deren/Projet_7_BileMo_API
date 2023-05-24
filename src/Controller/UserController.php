<?php

namespace App\Controller;

use App\Entity\Client;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Cache(maxage: 60,public:false,mustRevalidate: true)]
    #[Route('api/clients/{id}/users',name: 'app_user_list',methods: 'GET')]
    public function list(Client $client,UserService $userService, Request $request):JsonResponse
    {
        //might implement that as a voter!
        if($client !== $this->getUser()){
            throw  new AccessDeniedException("access forbidden",Response::HTTP_FORBIDDEN);
        }
        $page= (int)$request->query->get('page',1);
        $limit = (int)$request->query->get('limit',10);
        return $userService->PaginatedListUserJson($client,$page,$limit);
    }

}
