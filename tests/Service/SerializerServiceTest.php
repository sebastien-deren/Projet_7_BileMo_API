<?php

namespace App\Tests\Service;

use JMS\Serializer\Serializer;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\TestCase;

class SerializerServiceTest extends TestCase
{
    public function testPaginatorReturnJson(){

        $context = $this->getMockBuilder()
            ->disableOriginalConstructor()
            ->addMethods();

        $this->assertContains('first',$context);

    }
    public function testSomething(): void
    {
        $this->assertTrue(true);

    }
}
