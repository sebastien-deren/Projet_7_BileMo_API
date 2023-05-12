<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    private KernelBrowser|null $client;
    public function setUp():void
    {
        $this->client = static::createClient();
    }
    public function testProductListPaginatedReturnGoodJson(){
        $urlGenerator = $this->client->getContainer()->get('router.default');
        $this->client->request(Request::METHOD_GET,$urlGenerator->generate('app_product_list',["page"=>1,"limit"=>5]));
        $response = $this->client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue(is_array($responseData));
        $linksPagination=$responseData['_links'];
        echo($linksPagination);
        $this->assertTrue(
            (array_key_exists('self',$linksPagination))
            &&(array_key_exists('first',$linksPagination))
            &&(array_key_exists('last',$linksPagination)));
        $this->assertTrue(isset($responseData['_embedded']['items']));

    }
    public function testProductListFullReturnGoodJson(){
        $urlGenerator = $this->client->getContainer()->get('router.default');
        $this->client->request(Request::METHOD_GET,$urlGenerator->generate('app_product_list'));
        $response = $this->client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
    }
}
