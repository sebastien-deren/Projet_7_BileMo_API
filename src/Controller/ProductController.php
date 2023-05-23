<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\Headers\PaginationHeaderInterface;
use App\Service\ProductService;
use App\Service\SerializerService;
use http\Exception\BadQueryStringException;
use PHPUnit\Util\Exception;
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
     * @throws \Exception
     */
    #[Route('products/', name: 'app_product_list', methods: 'get')]
    public function list(Request      $request,
                         ProductService $productService): JsonResponse
    {

        $page = (int)($request->query->get('page', 1));
        $limit = (int)($request->query->get('limit', 10)) ;
        //need to test if Exception is catch by our eventSubscriber (or had smthg to catch it)

        return $productService->ProductListPaginatedJsonResponse($page,$limit);

    }
}
