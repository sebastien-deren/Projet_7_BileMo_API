<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Service\SerializerService;
use App\Service\UserService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/api/clients/{id}/users/{user_id}', name: 'app_user_delete', methods: 'delete')]
    public function delete(
        #[MapEntity(expr: 'repository.find(user_id)')]
        User        $user,
        SerializerService $serializer,
        Client      $client,
        UserService $service): JsonResponse
    {
        if ($client !== $this->getUser()) {
            throw new UnauthorizedHttpException('bearer token ');
        }
        $data = $service->delete($user, $client);
        if(!$data){
            return new JsonResponse('you don\'t have access to this client',Response::HTTP_FORBIDDEN,[],false);
        }
        return new JsonResponse($serializer->serialize('userDetail',$data), Response::HTTP_NO_CONTENT, [], true);
    }

}
