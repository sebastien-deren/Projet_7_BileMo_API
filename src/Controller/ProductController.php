<?php

namespace App\Controller;

use App\Service\ProductService;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ProductController extends AbstractController
{

    #[Route('/products/{id<\d+>}', name: 'app_product_details')]
    public function productDetails(
        int $id,
        SerializerService $serializer,
        ProductService $productService): JsonResponse
    {
        $response = new JsonResponse();
        $serializedData = $serializer->serializeOnce($productService->getOneById($id),'details');
        return $response->setJson($serializedData)->setStatusCode(Response::HTTP_OK)->setCache([
            'public'=>true,
            'etag'=>"product".$id,
            'max_age'=>3600,
        ]);;
    }
}
