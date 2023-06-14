<?php

namespace App\Test\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\ClientRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $repository;
    private Client $testClient;
    private string $path = '/api/users/';

    protected function setUp(): void
    {

        $this->client = static::createClient();
        $userRepository = static::getContainer()->get(ClientRepository::class);
        $this->testClient = $userRepository->findOneBy(["username" => 'green']);
        $this->client->loginUser($this->testClient);
        $this->repository = static::getContainer()->get('doctrine')->getRepository(User::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', sprintf('/api/clients/%s%s',$this->testClient,'/users/'));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('User index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'user[name]' => 'Testing',
            'user[firstName]' => 'Testing',
            'user[email]' => 'Testing',
            'user[phoneNumber]' => 'Testing',
            'user[street]' => 'Testing',
            'user[streetNumber]' => 'Testing',
            'user[zipCode]' => 'Testing',
            'user[city]' => 'Testing',
            'user[clients]' => 'Testing',
        ]);

        self::assertResponseRedirects('/users/');

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
