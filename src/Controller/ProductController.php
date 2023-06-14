<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\Headers\PaginationHeaderInterface;
use App\Service\ProductService;
use App\Service\SerializerService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class ProductController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    #[Cache(maxage: 3600, public: false, mustRevalidate: true)]
    #[Route('/api/products', name: 'app_product_list', methods: 'get')]
    public function list(Request           $request,
                         ProductService    $productService,
                         SerializerService $serializerService,
                         PaginationHeaderInterface $paginationHeader): JsonResponse
    {
        $page = (int)($request->query->get('page', 1));
        $limit = (int)($request->query->get('limit', 10));
        $productList = $productService->ProductListPaginatedJsonResponse($page, $limit);
        $response = new JsonResponse($serializerService->serialize('productList', $productList->data), Response::HTTP_OK, [], true);
        $paginationHeader->setHeaders($response, $productList, 'app_product_list');
        return $response;
    }
    #[Cache(maxage: 3600,public: false,mustRevalidate: true)]
    #[Route('/api/products/{id<\d+>}', name: 'app_product_details',methods: 'get')]
    public function productDetails(
        int $id,
        SerializerService $serializer,
        ProductService $productService): JsonResponse
    {
        $product = $productService->productDetail($id);
        if(null === $product){
            throw new RouteNotFoundException();
        }
        return new JsonResponse($serializer->serialize('productDetails',$product),Response::HTTP_OK,[],true);

    }
}
