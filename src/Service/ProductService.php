<?php declare(strict_types=1);

namespace App\Service;

use App\DTO\PaginationDto;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $repository,
    )
    {
    }

    /**
     * @return array<Product>
     * @throws InvalidArgumentException
     */
    public function productList(): array
    {
            $cache = new FilesystemTagAwareAdapter();
            return $cache->get('product_list', function (ItemInterface $item) {
                $item->tag('products');
                $item->expiresAfter(5000);
                return $this->repository->findAll();
            });
    }

    /**
     * @throws InvalidArgumentException
     */
    public function productListPaginatedCached(int $page, int $limit):PaginationDto
    {

        $cache = new FilesystemTagAwareAdapter();
        $cacheCallback = function (ItemInterface $item) use ($limit, $page) {
            $item->tag('products');
            $item->expiresAfter(3600);
            return $this->repository->findAllWithPagination($page,$limit);
        };
        return $cache->get('product_list'.$page.'limit'.$limit,$cacheCallback);


    }
    public function productListPaginated(int $page, int $limit):PaginationDto
    {
        return $this->repository->findAllWithPagination($page,$limit);
    }

}
