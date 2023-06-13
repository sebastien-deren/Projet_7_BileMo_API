<?php

namespace App\Entity;

use App\Listener\UserListener;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

//Problem with expressions trying to retrieve Username but couldn't find how

/**
 * @Serializer\XmlRoot("user")
 *
 * @Hateoas\Relation(
 *     "self",
 *     href= "expr('api/users/' ~ object.getId() )",
 *      exclusion= @Hateoas\Exclusion(groups="userList"))
 * @Hateoas\Relation(
 *     "list",
 *     href= "expr('api/clients/' ~ object.getClientName() ~ '/users/')",
 *     exclusion= @Hateoas\Exclusion(groups="userList"))
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\EntityListeners([UserListener::class])]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Serializer\Groups(['userList', 'userDetails'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Serializer\Groups(['userList', 'userDetails'])]
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[Serializer\Groups(['userList', 'userDetails'])]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[Serializer\Groups(['userDetails'])]
    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[Serializer\Groups(['userDetails'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $street = null;

    #[Serializer\Groups(['userDetails'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $streetNumber = null;

    #[Serializer\Groups(['userDetails'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $zipCode = null;

    #[Serializer\Groups(['userDetails'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[Serializer\Groups(['none'])]
    #[ORM\ManyToMany(targetEntity: Client::class, inversedBy: 'users')]
    private Collection $clients;

    #[Serializer\Groups(['none'])]
    private ?string $currentClientName = null;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }
    public function getClientName():string
    {
        return $this->currentClientName;
    }
    public function setClientName(string $clientName):self
    {
        $this->currentClientName = $clientName;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(?string $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }
    public function initializeClients():self{
        $this->clients = new ArrayCollection();
        return $this;
    }

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        $this->clients->removeElement($client);

        return $this;
    }
}
