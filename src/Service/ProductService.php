<?php

namespace App\Service;

class ProductService
{
    public function __construct(
        private ProductRepository $repository,
    )
    {
    }

    public function productList(): Product
    {
        return $this->repository->findAll();

    }

}