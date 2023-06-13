<?php

namespace App\Listener;

use App\Entity\Client;
use App\Entity\User;
use App\Service\CacheService;
use App\Service\UserService;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as On;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\SecurityBundle\Security;


class UserListener
{
    public function __construct(
        private CacheService $cacheService,
        private UserService  $userService,
        private Security     $security)
    {
    }

    #[On\PreUpdate]
    public function clearUpdatedCache(User $user, PreUpdateEventArgs $eventArgs)
    {
        if ($eventArgs->hasChangedField('Client')) {
            $oldClients = $eventArgs->getOldValue('Client');
            $newClients = $eventArgs->getNewValue('Client');
            $client = $this->findChangedClient($oldClients, $newClients);
            $this->cacheService->destructCacheByTags(['userList' . $client->getId()]);
            $this->cacheService->destructCacheByName(($this->userService->cacheNameUserDetail($user->getId(), $client->getId())));
        }
        //other changes to users that need cache Clearing can go there

    }

    private function findChangedClient(Collection $oldClients, Collection $newClients): Client
    {
        return $oldClients->filter(function ($client) use ($newClients) {
            return !$newClients->contains($client);
        })->first() ?? throw new \Exception("not Attainable Exception");

    }


    #[On\PostPersist]
    public function clearCache(User $user, PostUpdateEventArgs|PostPersistEventArgs $eventArgs): void
    {
        $clients = $user->getClients();
        $cacheNames = [];
        foreach ($clients as $client) {
            $cacheNames[] = 'userList' . $client->getId();
        }
        $this->cacheService->destructCacheByTags($cacheNames);

    }

    #[On\PreRemove]
    public function clearCacheDeletedUser(PreRemoveEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        $client = $entity->getClients()->first();
        $this->cacheService->destructCacheByTags(['userList' . $client->getId()]);
        $this->cacheService->destructCacheByName(($this->userService->cacheNameUserDetail($entity->getId(), $client->getId())));
    }


    #[On\PostLoad]
    #[NoReturn] public function postLoadEvent(User $user, PostLoadEventArgs $eventArgs): void
    {
        $user->setClientName($this->security->getUser()->getUserIdentifier());
    }
}
