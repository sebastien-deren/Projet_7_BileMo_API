<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;

use App\Service\SerializerService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Headers\PaginationHeaderInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Attribute\Cache;


class UserController extends AbstractController
{

    #[Cache(maxage: 60, public: false, mustRevalidate: true)]
    #[Route('/api/clients/{username}/users', name: 'app_user_list', methods: 'get')]
    public function list(
        Client                    $client,
        UserService               $userService,
        Request                   $request,
        SerializerService         $serializerService,
        PaginationHeaderInterface $paginationHeader): JsonResponse
    {
        if ($client !== $this->getUser()) {
            throw  new AccessDeniedException("access forbidden", Response::HTTP_FORBIDDEN);
        }
        $page = (int)$request->query->get('page', 1);
        $limit = (int)$request->query->get('limit', 10);
        $paginatedUser = $userService->PaginatedListUser($client, $page, $limit);
        $response = new JsonResponse($serializerService->serialize('userList', $paginatedUser->data), Response::HTTP_OK, [], true);

        $paginationHeader->setHeaders($response, $paginatedUser, 'app_user_list',["username"=> $client->getUsername()]);
        return $response;
    }

    #[Cache(maxage: 60, public: false, mustRevalidate: true)]
    #[Route('api/users/{id}', name: "app_user_detail", methods: "GET")]
    public function detail(
        int $id,
        SerializerService $serializerService,
        UserService   $service
    ): JsonResponse
    {
        $user = $service->getValidUser($id, $this->getUser());
        return new JsonResponse($serializerService->serialize('userList',$user),Response::HTTP_OK,[],true);
    }

    #[Route('api/users', name:'app_user_create',methods: 'POST')]
    public function create(
        UserService $userService,
        Request $request,
        SerializerService $serializer)
    {
        $user = $serializer->deserialize($request->getContent(),User::class,'json');
        $user = $userService->create($user,$this->getUser());
        $jsonUser = $serializer->serialize('userDetail',$user);
        //$location = $this->generateUrl( 'app_user_detail',['id'=>$user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonUser,Response::HTTP_CREATED,["location"=>'$location'],true);
    }

}
