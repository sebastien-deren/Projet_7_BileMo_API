<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\CacheService;
use App\Service\ProductService;
use App\Service\SerializerService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[Route('/api/')]
class ProductController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     */
    #[Route('products/', name: 'app_product_list', methods: 'get')]
    public function list(Request                   $request,
                         CacheService $cache): JsonResponse
    {

        $page = (int)$request->query->get('page', 0);
        $limit = (int)$request->query->get('limit', 5);

        if (0 === $page || $limit > 100) {

            return new JsonResponse($cache->getProductListCached(), Response::HTTP_OK, [], true);
        }

        return new JsonResponse($cache->getProductPagesCached($page,$limit), Response::HTTP_OK, [], true);

    }
}
