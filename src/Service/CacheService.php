<?php

namespace App\Service;

use App\Entity\Product;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CacheService
{

    public function __construct(
        private readonly TagAwareCacheInterface $cache,
        private readonly SerializerService      $serializer,
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function cacheUniqueProductJson(Product $product): string
    {
        $callback = function (CacheItem $item) use ($product) {
            $item->expiresAfter(3600);
            return $this->serializer->serializeOnce($product, 'product_detail');
        };
        return $this->cache->get('product' . $product->getId(), $callback);

    }
}
