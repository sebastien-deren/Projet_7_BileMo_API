<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    /**
     * @return void
     *
     * The ProductService Call our Repository and cache the data,
     * I think what could be usefull to test is to see if our Data is cached correctly,
     * By calling one time our service (who will cache data)
     * then a second time to see if our data is well cached
     * then delete our cache tag and see if the data is retrieve from our callback (and not the cache)
     */
    public function testSomething(): void
    {
        $this->assertTrue(true);

    }
}
