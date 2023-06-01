<?php declare(strict_types=1);

namespace App\Repository;

use App\DTO\PaginationDto;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Hateoas\Representation\PaginatedRepresentation;
use Symfony\Component\HttpFoundation\Response;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {

        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findAllWithPagination(int $page = 1 , int $limit =5):PaginationDto
    {
        if($limit <= 0 ){
            throw new \Exception("limit must be a positive integer");
        }
        $count = $this->createQueryBuilder('a')
            ->select('count(a.id)' )
            ->getQuery()
            ->getSingleScalarResult();
        $maxpage = (int)($count/$limit)+1;
        if($page>$maxpage){
            throw new \Exception("you tried to retrieve non-existing data",Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE);
        }
        $query = $this->createQueryBuilder('paginagation')
            ->setFirstResult(($page-1)*$limit)
            ->setMaxResults($limit);
        return new PaginationDto($page,$limit,$maxpage,$query->getQuery()->getResult());

    }
}
