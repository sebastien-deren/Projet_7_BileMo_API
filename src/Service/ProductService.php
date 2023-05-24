<?php declare(strict_types=1);

namespace App\Service;

use App\Repository\ProductRepository;
use App\Service\CacheService;
use App\Service\Headers\PaginationHeaderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $repository,
        private SerializerService          $serializerService,
        private  PaginationHeaderInterface $paginationHeader,
        private CacheService $cacheservice,
    )
    {
    }

    public function productDetailJsonResponse(int $id):JsonResponse
    {

        $cacheName = 'product'.$id;
        $dataToGet= function (array $param){
            $product = $this->repository->find($param['id']);
            return new JsonResponse($this->serializerService->serialize($product,"productDetail"));
        };
        return $this->cacheservice->getCachedData($dataToGet,$cacheName,'product'.$id,['id'=>$id]);
    }
    /**
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function ProductListPaginatedJsonResponse(int $page, int $limit): JsonResponse
    {
        if ($limit <= 0) {
            throw new \Exception("limit must be a positive integer", Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE);
        }
        if ($limit > 1000) {
            throw new \OutOfRangeException("You tried to request too much data", Response::HTTP_REQUEST_ENTITY_TOO_LARGE);
        }
        if ($page <= 0) {
            throw new \Exception("page must be a positive integer", Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE);
        }

        $cacheName = 'productList-page' . $page . "-limit" . $limit;
        $dataToGet = function (array $param) {
            $productList = $this->repository->findAllWithPagination($param['page'], $param['limit']);
            $response = new JsonResponse($this->serializerService->serialize($productList->data,'productList'), Response::HTTP_OK, [], true);
            $this->paginationHeader->setHeaders($response, $productList, 'app_product_list');
            return $response;
        };
        return $this->cacheservice->getCachedData($dataToGet, $cacheName, 'productList', ['page' => $page, 'limit' => $limit]);
    }

}
