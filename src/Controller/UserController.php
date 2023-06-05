<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Service\Headers\PaginationHeaderInterface;
use App\Service\SerializerService;
use App\Service\UserService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{

    #[Cache(maxage: 60, public: false, mustRevalidate: true)]
    #[Route('/api/clients/{username}/users', name: 'app_user_list', requirements: ['id' => '\d+'], methods: 'get')]
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
        $paginatedUser = $userService->PaginatedListUserJson($client, $page, $limit);
        $response = new JsonResponse($serializerService->serialize('userList', $paginatedUser->data), Response::HTTP_OK, [], true);
        $paginationHeader->setHeaders($response, $paginatedUser, 'app_user_list',["username"=> $client->getUsername()]);
        return $response;
    }

}
