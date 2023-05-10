<?php

namespace App\Controller;

use App\Service\ProductService;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[route('product/')]
class ProductController extends AbstractController
{
    #[Route('list/', name: 'app_product_list')]
    public function list(ProductService $productService, serializerInterface $serializer): JsonResponse
    {
        $productService->productList();
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }
}
