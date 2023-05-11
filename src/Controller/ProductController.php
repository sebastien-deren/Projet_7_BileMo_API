<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\ProductService;
use JMS\Serializer\SerializerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     */
    #[Route('products/', name: 'app_product_list',methods:'get')]
    public function list(ProductService $productService, SerializerInterface $serializer,ProductRepository $repository): JsonResponse
    {
        try {
            $productList = $productService->productList();
        }catch (InvalidArgumentException)
        {
            return $this->json('Invalid Argument error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }
}
