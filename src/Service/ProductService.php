<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $repository,
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function productList(): Product
    {
        $cache = new FilesystemAdapter();
        return $cache->get('product_list',function(ItemInterface $item){
            $item->expiresAfter('5000');
            return $this->repository->findAll();
        });
    }

}