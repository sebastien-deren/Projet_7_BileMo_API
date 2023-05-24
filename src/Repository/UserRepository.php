<?php

namespace App\Repository;

use App\DTO\PaginationDto;
use App\Entity\Client;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function getPaginateUsers(Client $client,int $page,int $limit):PaginationDto{
        if ($limit < 0) {
            throw new \Exception("limit must be a positive integer", Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE);
        }
        if ($limit > 1000) {
            throw new \OutOfRangeException("You tried to request too much data", Response::HTTP_REQUEST_ENTITY_TOO_LARGE);
        }
        if ($page < 0) {
            throw new \Exception("page must be a positive integer", Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE);
        }
        $users= $client->getUsers();
        if(($page*$limit)>$users->count()){
            throw new \OutOfRangeException("the data you requested does not exist",Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE);
        }
        $data = $users->slice(($page-1)*$limit,$limit);
        $maxPage= (int) $users->count()/$limit;
        return new PaginationDto($page,$limit,$maxPage,$data);

    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
