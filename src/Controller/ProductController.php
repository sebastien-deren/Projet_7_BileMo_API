<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\SerializerService;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Route('/api')]
class ProductController extends AbstractController
{
    #[Route('/products/{id<\d+>}', name: 'app_product_details')]
    public function productDetails(
        Product $product,
        SerializerService $serializer,
        TagAwareCacheInterface $cache): JsonResponse
    {


        $context = SerializationContext::create()->setGroups(['details']);
        $serializedData = $serializer->serialize($product,'json',$context);
        return new JsonResponse($serializedData,Response::HTTP_OK,[],true);
    }
}
