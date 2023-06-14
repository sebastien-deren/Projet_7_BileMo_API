<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use App\Service\Headers\PaginationHeaderInterface;
use App\Service\ProductService;
use App\Service\SerializerService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use phpDocumentor\Reflection\Types\String_;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class ProductController extends AbstractController
{
    /**
     * Get a List of product paginated by page and limit
     *
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    #[Cache(maxage: 3600, public: false, mustRevalidate: true)]

    #[Route('/api/products', name: 'app_product_list', methods: 'get')]
    #[OA\Response(
        response: 200,
        description: 'return a paginated list of Product Summarized',
        content: new Oa\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Product::class, groups: ['productList']))
        )
    )]
    #[OA\Parameter(
        name: 'page',
        description: 'The page you want to see',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'limit',
        description: 'the number of item you want to see by page (limited to 100)',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'Product')]
    #[Security(name: 'Bearer')]
    public function list(Request                   $request,
                         ProductService            $productService,
                         SerializerService         $serializerService,
                         PaginationHeaderInterface $paginationHeader): JsonResponse
    {
        $page = (int)($request->query->get('page', 1));
        $limit = (int)($request->query->get('limit', 10));
        $productList = $productService->productListPaginatedJsonResponse($page, $limit);
        $response = new JsonResponse($serializerService->serialize('productList', $productList->data), Response::HTTP_OK, [], true);
        $paginationHeader->setHeaders($response, $productList, 'app_product_list');
        return $response;
    }


    /**
     * Get a Product Details
     *
     * @param int $id
     * @param SerializerService $serializer
     * @param ProductService $productService
     * @return JsonResponse
     */
    #[Cache(maxage: 3600, public: false, mustRevalidate: true)]
    #[OA\Response(
        response: 200,
        description: 'return a paginated list of Product',
        content: new Model(type: Product::class,groups: ['productDetails'])

    )]
    #[OA\Tag(name: 'Product')]

    #[Route('/api/products/{id<\d+>}', name: 'app_product_details', methods: 'get')]
    public function productDetails(
        int               $id,
        SerializerService $serializer,
        ProductService    $productService): JsonResponse
    {
        $product = $productService->productDetail($id);
        if(null === $product){
            throw new RouteNotFoundException();
        }
        return new JsonResponse($serializer->serialize('productDetails',$product),Response::HTTP_OK,[],true);

    }
}
