<?php declare(strict_types=1);

namespace App\Service;

use App\DTO\PaginationDto;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Psr\Cache\InvalidArgumentException;
class ProductService
{
    public function __construct(
        private readonly ProductRepository $repository,
        private CacheService               $cacheservice,
    )
    {
    }

    public function productDetail(int $id): Product|null
    {

        $cacheName = $this->cacheNameDetail($id);
        $dataToGet = function (array $param) {
            return $this->repository->find($param['id']);
        };
        return $this->cacheservice->getCachedData($dataToGet, $cacheName, 'product' . $id, ['id' => $id]);
    }

    /**
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function productListPaginatedJsonResponse(int $page, int $limit): PaginationDto
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

        $cacheName = $this->cacheNamePage($page, $limit);
        $dataToGet = function (array $param) {
            return $this->repository->findAllWithPagination($param['page'], $param['limit']);
        };
        return $this->cacheservice->getCachedData($dataToGet, $cacheName, 'productList', ['page' => $page, 'limit' => $limit]);


    }

    public function cacheNamePage(int $page,int $limit): string
    {
        return 'productList-page' . $page . "-limit" . $limit;
    }

    public function cacheNameDetail(int $id): string
    {
        return 'product' . $id;
    }


}
