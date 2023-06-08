<?php

namespace App\Listener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\PostLoadEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\Mapping as ORM;

#[AsEntityListener(event: Events::postLoad, method: 'postLoadEvent', entity: User::class)]
class UserListener
{
    public function __construct(private Security $security)
    {
    }
    #[NoReturn] public function postLoadEvent(User $user,PostLoadEventArgs $eventArgs):void
    {
        $user->setClientName($this->security->getUser()->getUserIdentifier());
    }
}
