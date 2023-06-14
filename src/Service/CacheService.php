<?php

namespace App\Service;

use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

Class CacheService
{
    public function __construct(private readonly TagAwareCacheInterface $cache){}

    public function getCachedData(\Closure $dataToGet,string $cacheName,?string $tag =null,?array $closureParam=null):mixed{
        return $this->cache->get($cacheName,function(ItemInterface $item)use($tag, $dataToGet,$closureParam){
            if(null !== $tag){
                $item->tag($tag);
            }
            $item->expiresAfter(3600);
            return $dataToGet($closureParam);
        });
    }

    /**
     * @throws InvalidArgumentException
     */
    public function destructCacheByName(?string $cacheName=null):void
    {
        $this->cache->delete($cacheName);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function destructCacheByTags(?array $cacheTags= null):void
    {
        $this->cache->invalidateTags($cacheTags);
    }

}
