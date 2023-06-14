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

        $this->client = static::createClient(["base_uri"=>'http://127.0.0.1:8000']);
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
        $this->markTestIncomplete();
        $crawler = $this->client->request(Request::METHOD_GET,$this->urlGenerator->generate('app_user_list',['username'=>$this->testClient->getUsername()]));

        self::assertResponseStatusCodeSame(200);

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $newUser = (new User())
            ->setEmail('green@test.com')
            ->setName('Monnom')
            ->setFirstName('prenom')
            ->setPhoneNumber('09000122222222')
            ->addClient($this->testClient);

        $this->client->jsonRequest(Request::METHOD_POST,  $this->urlGenerator->generate('app_user_create'),[($newUser)]);


        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);


        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new User();
        $fixture->setName('My Title');
        $fixture->setFirstName('My Title');
        $fixture->setEmail('My Title');
        $fixture->setPhoneNumber('My Title');
        $fixture->setStreet('My Title');
        $fixture->setStreetNumber('My Title');
        $fixture->setZipCode('My Title');
        $fixture->setCity('My Title');
        $fixture->setClients('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new User();
        $fixture->setName('My Title');
        $fixture->setFirstName('My Title');
        $fixture->setEmail('My Title');
        $fixture->setPhoneNumber('My Title');
        $fixture->setStreet('My Title');
        $fixture->setStreetNumber('My Title');
        $fixture->setZipCode('My Title');
        $fixture->setCity('My Title');
        $fixture->setClients('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'user[name]' => 'Something New',
            'user[firstName]' => 'Something New',
            'user[email]' => 'Something New',
            'user[phoneNumber]' => 'Something New',
            'user[street]' => 'Something New',
            'user[streetNumber]' => 'Something New',
            'user[zipCode]' => 'Something New',
            'user[city]' => 'Something New',
            'user[clients]' => 'Something New',
        ]);

        self::assertResponseRedirects('/users/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getFirstName());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getPhoneNumber());
        self::assertSame('Something New', $fixture[0]->getStreet());
        self::assertSame('Something New', $fixture[0]->getStreetNumber());
        self::assertSame('Something New', $fixture[0]->getZipCode());
        self::assertSame('Something New', $fixture[0]->getCity());
        self::assertSame('Something New', $fixture[0]->getClients());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new User();
        $fixture->setName('My Title');
        $fixture->setFirstName('My Title');
        $fixture->setEmail('My Title');
        $fixture->setPhoneNumber('My Title');
        $fixture->setStreet('My Title');
        $fixture->setStreetNumber('My Title');
        $fixture->setZipCode('My Title');
        $fixture->setCity('My Title');
        $fixture->setClients('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/users/');
    }
}
