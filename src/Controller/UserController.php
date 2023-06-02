<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;

use App\Service\SerializerService;
use App\Service\UserService;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserController extends AbstractController
{


    #[Route('api/clients/{username}/users', name:'app_user_create',methods: 'POST')]
    public function create(
        Client $client,
        UserService $userService,
        Request $request,
        SerializerService $serializer)
    {
        $user = $serializer->deserialize($request->getContent(),User::class,'json');
        $user = $userService->create($user,$this->getUser());
        $jsonUser = $serializer->serialize('userDetail',$user);
        $location = $this->generateUrl( 'app_user_detail',
            ['id'=>$user->getId(),'username'=>$client->getUserIdentifier()],
            UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonUser,Response::HTTP_CREATED,["location"=>$location],true);
    }

}
