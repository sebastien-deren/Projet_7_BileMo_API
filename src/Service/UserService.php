<?php

namespace App\Service;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserService
{
    public function __construct(
        private UserRepository      $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface       $urlGenerator
    )
    {
    }

    public function create(mixed $content,Client $client):JsonResponse
    {
        $user = $this->serializer->deserialize($content,User::class,'json');
        $this->repository->save($user,true);
        $jsonUser = $this->serializer->serialize($user,'json',['groups'=>'userDetail']);
        $location = $this->urlGenerator->generate(
            'userDetail',
            ['id'=>$jsonUser->getId(),'username'=>$client->getUserIdentifier()],
            UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonUser,Response::HTTP_CREATED,["location"=>$location],true);

    }

}
