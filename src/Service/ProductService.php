<?php

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
            $cache->invalidateTags(['products']);
            return $cache->get('product_list', function (ItemInterface $item) {
                $item->tag('products');
                $item->expiresAfter(5000);
                return $this->repository->findAll();
            });
    }
    public function productListPaginated(int $page, int $limit):PaginationDto
    {

        $cache = new FilesystemTagAwareAdapter();
        return $this->repository->findAllWithPagination($page,$limit);

    }

}
