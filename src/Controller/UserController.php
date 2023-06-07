<?php

namespace App\Controller;

use App\Service\ClientService;
use App\Service\serializerService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class UserController extends AbstractController
{
    #[Cache(maxage: 60, public: false, mustRevalidate: true)]
    #[Route('api/users/{id/+}', name: "app_user_detail", methods: "GET")]
    public function detail(
        int $id,
        SerializerService $serializerService,
        UserService   $service,
    ): JsonResponse
    {
        $user = $service->getValidUser($id, $this->getUser());
        return new JsonResponse($serializerService->serialize('userList',$user),Response::HTTP_OK,[],true);
    }
}
