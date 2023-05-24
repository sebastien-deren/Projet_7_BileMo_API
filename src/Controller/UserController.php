<?php

namespace App\Controller;

use App\Service\ClientService;
use App\Service\serializerService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class UserController extends AbstractController
{
    #[Route('client/{clientId}/users/{id/+}', name: "app_user_detail", methods: "GET")]
    public function detail(
        int               $id,
        int               $clientId,
        UserService       $service,
        ClientService     $clientService,
        serializerService $serializer) : JsonResponse
    {

        $client = $clientService->getValidClient($this->getUser(), $clientId);
        $user = $service->getValidUser($id, $client);
        $jsonRepsonse = $service->detailJsonResponse($user);
        //how to cache this without SensioFrameworkbundle to secure our route. We need to not use the HTTPCache but to actually hit our app to verify authentication first!

        return $jsonRepsonse;
    }
}
