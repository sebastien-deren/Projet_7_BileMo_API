<?php

namespace App\Test\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use App\Service\SerializerService;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $repository;
    private Client $testClient;
    private string $path = '/api/users/';
    private UrlGeneratorInterface $urlGenerator;
    private SerializerService $serializer;

    protected function setUp(): void
    {

        $this->client = static::createClient();
        $this->repository = static::getContainer()->get(UserRepository::class);
        $userRepository = static::getContainer()->get(ClientRepository::class);
        $this->testClient = $userRepository->findOneBy(["username" => 'green']);
        $this->client->loginUser($this->testClient);
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }

    }

    public function testIndex(): void
    {
        self::markTestIncomplete();
        $route = $this->urlGenerator->generate('app_user_list', ['username' => 'green', 'page' => 1, 'limit' => 10]);
        dump($route);
        $this->client->request(Request::METHOD_GET, 'api/clients/'.$this->testClient->getUsername().'/users');
        $response = $this->client->getResponse();
        dump($this->testClient->getUsername());

        self::assertResponseStatusCodeSame(200);

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    /**
     * @dataProvider newUser
     * @param array $array
     * @return void
     */
    public function testNew(array $array): void
    {
        self::markTestIncomplete();
        $originalNumObjectsInRepository = count($this->repository->findAll());
        $this->client->jsonRequest(Request::METHOD_POST, $this->urlGenerator->generate('app_user_create'), $array);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);


        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    /**
     * @dataProvider newUser
     * @param object $user
     * @return void
     */
    public function testShow(array $array): void
    {

        $user = $this->createUser($array,$this->testClient);
        $this->client->request('GET', sprintf('%s%s', $this->path, $user->getId()));

        self::assertResponseStatusCodeSame(200);
        $this->repository->remove($user);
    }

    /**
     * @dataProvider newUser
     * @return void
     */
    public function testRemove(array $array): void
    {

        $originalNumObjectsInRepository = count($this->repository->findAll());
        $user = $this->createUser($array,$this->testClient);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('DELETE', sprintf('%s%s', $this->path, $user->getId()));

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }


    public
    function newUser(): array
    {
        return array(
            array(array('name'=>'name','firstname'=>'firstname','email'=>'email@example.com','phoneNumber'=>'00000000000')),
        );
    }
    public function createUser(array $array,Client $client){
        $user = (new User)->setName($array['name'])->setFirstName($array['firstname'])->setEmail($array['email'])->setPhoneNumber($array['phoneNumber']);
        $user->addClient($client);
        $this->repository->save($user,true);
        return $user;
    }
}
