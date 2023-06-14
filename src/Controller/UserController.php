<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Service\CacheService;
use App\Service\SerializerService;
use App\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use PhpParser\Node\Expr\AssignOp\Mod;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use App\Service\Headers\PaginationHeaderInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use OpenApi\Attributes as OA;


class UserController extends AbstractController
{




    /**
     * Get a List of User
     *
     * @param Client $client
     * @param UserService $userService
     * @param Request $request
     * @param SerializerService $serializerService
     * @param PaginationHeaderInterface $paginationHeader
     * @return JsonResponse
     */
    #[OA\Response(
        response: 200,
        description: 'return a paginated list of User Summarized',
        content: new Oa\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: User::class, groups: ['userList']))
        )
    )]
    #[OA\Tag(name: 'User')]
    #[Security(name: 'Bearer')]
    #[OA\QueryParameter(name: 'page', description: 'page number', schema: new OA\Schema(type:'string'))]
    #[OA\QueryParameter(name: 'limit', description: 'number of user per pages', schema: new OA\Schema(type:'string'))]
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
        $paginatedUser = $userService->paginatedListUser($client, $page, $limit);
        $response = new JsonResponse($serializerService->serialize('userList', $paginatedUser->data), Response::HTTP_OK, [], true);
        $paginationHeader->setHeaders($response, $paginatedUser, 'app_user_list', ["username" => $client->getUsername()]);
        return $response;
    }
    /**
     * Get a User details
     * @param int $id
     * @param SerializerService $serializerService
     * @param UserService $service
     * @return JsonResponse
     */
    #[OA\Response(
        response: 200,
        description: 'Return the User Entity with details',
        content: new Model(type: User::class,groups: ['userDetails'])
    )]
    #[Cache(maxage: 10, public: false, mustRevalidate: true)]
    #[Route('api/users/{id}', name: "app_user_detail", methods: "GET")]
    public function detail(
        int               $id,
        SerializerService $serializerService,
        UserService       $service
    ): JsonResponse
    {
        $user = $service->getValidUser($id, $this->getUser());
        return new JsonResponse($serializerService->serialize('userDetails', $user), Response::HTTP_OK, [], true);
    }

    /**
     * Delete a user from the client's User Collection
     *
     * If the user is linked to the current client only delete the user
     * @param User $user
     * @param SerializerService $serializer
     * @param UserService $service
     * @return JsonResponse
     */
    #[OA\Response(response: 204,description: 'Confirmation that the user is deleted')]
    #[Route('/api/users/{id}', name: 'app_user_delete', methods: 'delete')]
    public function delete(
        User              $user,
        SerializerService $serializer,
        UserService       $service): JsonResponse
    {
        $data = $service->delete($user, $this->getUser());
        if (!$data) {
            return new JsonResponse('you don\'t have access to this client', Response::HTTP_FORBIDDEN, [], false);
        }
        return new JsonResponse($serializer->serialize('userDetail', $data), Response::HTTP_NO_CONTENT, [], true);
    }
    /**
     * Create a new User
     *
     * The **four** First field are required.
     * The adress is optionnal.
     *
     * @param UserService $userService
     * @param Request $request
     * @param SerializerService $serializer
     * @return JsonResponse
     */
    #[OA\Response(response: 201,
        description: 'the User you just Created',
        content: new Model(type: User::class,groups: ['userDetails']))]
    #[OA\RequestBody(
        description: 'the User you want to create',
        required: true,
        content: new OA\JsonContent(ref: new Model(type: User::class,groups: ['userDetails'])))]

    #[Route('api/users', name:'app_user_create',methods: 'POST')]
    public function create(
        UserService $userService,
        Request $request,
        SerializerService $serializer)
    {
        $user = $serializer->deserialize($request->getContent(),User::class,'json');
        $user = $userService->create($user,$this->getUser());
        $jsonUser = $serializer->serialize('userDetails',$user);
        $location = $this->generateUrl( 'app_user_detail',['id'=>$user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonUser,Response::HTTP_CREATED,["location"=>$location],true);
    }

}
