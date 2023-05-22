<?php

namespace App\Service;

use App\Repository\ClientRepository;
use App\Repository\ProductsRepository;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class ProductService
{
    public function __construct(private ProductsRepository $repository){}
    public function getOneById(int $id){
        //we have our errorListener in our other branch isok
        return $this->repository->find($id) ?? throw new RouteNotFoundException();

    }

}
