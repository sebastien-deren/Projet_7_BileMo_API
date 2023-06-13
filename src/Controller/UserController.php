<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\SerializerService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Client;
use App\Service\Headers\PaginationHeaderInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;


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
}
