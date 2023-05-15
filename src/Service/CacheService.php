<?php

namespace App\Service;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CacheService
{
    public function __construct(
        private TagAwareCacheInterface $cache,
        private ProductService         $productService,
        private SerializerService      $serializerService,)
    {

    }

    /**
     * @return string
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getProductListCached():string{
        $cacheName = 'productList';
        $callback = function ($item)  {
            $item->tag('productsList');
            $item->expiresAfter(3600);
            $productData = $this->productService->productList();
            return $this->serializerService->serializeList($productData);
        };
         return $this->cache->get($cacheName,$callback);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return string
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getProductPagesCached(int $page,int $limit):string{
        $cacheName = 'productList-page' . $page . "-limit" . $limit;
        $callback = function(ItemInterface $item) use ($limit, $page) {
            $item->tag('productsList');
            $item->expiresAfter(3600);
            $paginationObject = $this->productService->productListPaginated($page, $limit);
            return $this->serializerService->paginator('productList', $paginationObject);
        };
        return $this->cache->get($cacheName,$callback);

    }

}
