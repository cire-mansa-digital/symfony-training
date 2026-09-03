<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    };

    public function findByDurationShort(int $duration): array
    {
        return $this->createQueryBuilder("r")
            //  ->select('r','c')
            //  ->leftJoin("r.category","c")
            ->where("r.duration <= :duration")
            //  ->where("c.slug = :cat")
            ->orderBy("r.duration", "ASC")
            ->setParameter("duration", $duration)
            //  ->setParameter("cat",'dessert-au-repo')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult();
    }

    public function  paginateRecipe(int $page, int $limit): Paginator
    {

        $query = $this->createQueryBuilder('r')
            ->setFirstResult(($page - 1)* $limit)
            ->setMaxResults($limit);

        return  new Paginator($query);
    }

    public function totalDuration()
    {
        return $this->createQueryBuilder("r")
            ->select("SUM(r.duration) as total")
            ->getQuery()
            ->getScalarResult();
    }
}
