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

    public function productList(): array
    {
        return $this->repository->findAll();
    }

    public function productListPaginated(int $page, int $limit): PaginationDto
    {
        return $this->repository->findAllWithPagination($page, $limit);
    }

}
