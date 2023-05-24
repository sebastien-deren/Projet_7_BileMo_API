<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\ProductService;
use App\Service\SerializerService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Route('/api/')]
class ProductController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    #[Cache(maxage:3600,public:false,mustrevalidate:true)]
    #[Route('products/', name: 'app_product_list', methods: 'get')]
    public function list(Request      $request,
                         ProductService $productService): JsonResponse
    {
        $page = (int)($request->query->get('page', 1));
        $limit = (int)($request->query->get('limit', 10));
        return $productService->ProductListPaginatedJsonResponse($page, $limit);


    }
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
