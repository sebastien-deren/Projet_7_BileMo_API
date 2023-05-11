<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\ProductService;
use App\Service\SerializerService;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\Context;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/')]
class ProductController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     */
    #[Route('products/', name: 'app_product_list', methods: 'get')]
    public function list(ProductService      $productService,
                         SerializerService   $serializerService,
                         Request             $request,
                         SerializerInterface $serializer): JsonResponse
    {

        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 5);
        $paginationObject = $productService->productListPaginated($page, $limit);
        $serializedData = $serializerService->paginator('productList', $paginationObject);

        return new JsonResponse($serializedData, Response::HTTP_OK, [], true);

    }
}
