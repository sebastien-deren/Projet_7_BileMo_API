<?php

namespace App\Tests\Controller;

use App\Entity\Client;
use App\Entity\Product;
use App\Repository\ClientRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductControllerTest extends WebTestCase
{
    private KernelBrowser|null $client;
    private UrlGeneratorInterface $urlGenerator;

    private Product|null $testProduct;
    private Client|null $testUser;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(ClientRepository::class);
        $this->testUser = $userRepository->findOneBy(["username" => 'green']);
        $productRepository = static::getContainer()->get(ProductRepository::class);
        $this->testProduct = $productRepository->findOneBy(["brand"=>"LG"]);
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }

    public function testProductListPaginatedReturnGoodJson()
    {
        $this->client->loginUser($this->testUser);
        $limit = Rand(1, 10);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('app_product_list', ["page" => 1, "limit" => $limit]));
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $responseDataJson = $response->getContent();
        $this->assertJson($responseDataJson);
        $responseData = json_decode($responseDataJson, true);
        $this->assertTrue(is_array($responseData));
        $this->assertTrue(count($responseData) === $limit);
    }
    public function testProductDetailReturnGoodJson()
    {
        $this->client->loginUser($this->testUser);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('app_product_details', ['id'=>$this->testProduct->getId()]));
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
        $responseDataJson = $response->getContent();
        $this->assertJson($responseDataJson);
        $responseData = json_decode($responseDataJson, true);
        $this->assertTrue(is_array($responseData));
    }
    public function testProductListNotLogged()
    {
        $this->client->request(Request::METHOD_GET,$this->urlGenerator->generate('app_product_list'));
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_UNAUTHORIZED,$response->getStatusCode());
    }

    /**
     * @dataProvider badRequest
     * @return void
     */
    public function testProductListPaginatedBadRequest(array $query)
    {
        $this->client->loginUser($this->testUser);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('app_product_list', $query));
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($response->getContent());
    }
    public function badRequest():array
    {
        return [
            [["page" => -1]],
            [["page"=>0,"limit"=>5]],
            [["page"=>'string',"limit"=>5]],
            [["limit"=>"string"]],
            [["limit" => 0]],
            [["limit"=>-10]],
            [["page" => 10000, "limit" => 100]],
            [["limit" => 1000000]],
        ];

    }


}
