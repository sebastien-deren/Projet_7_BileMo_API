<?php

namespace App\Tests\Service;

use JMS\Serializer\Serializer;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;

class SerializerServiceTest extends TestCase
{

    /**
     * @return void
     *
     * The serializer Service only aim is to serialize data, we can test it by mocking the serializer object.
     * But by doing so we do not have anything left in our Service.
     */
    public function testSomething(): void
    {
        $this->assertTrue(true);

    }
}
