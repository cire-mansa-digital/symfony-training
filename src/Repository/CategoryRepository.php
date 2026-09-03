<?php

namespace App\Repository;

use App\Entity\Category;
use App\DTO\CategoryWithNombre;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    //    /**
    //     * @return Category[] Returns an array of Category objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Category
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


        /**
         * Method findWithNombre
         *
         * @return CategoryWithNombre[]
         */
        public function findWithNombre() : array{

          return $this->createQueryBuilder('c')
          ->select(' NEW App\\DTO\\CategoryWithNombre(c.id , c.name , c.slug , COUNT(c.id) ) ')
          ->leftJoin('c.recipes','r')
          ->groupBy('c.id')
          ->getQuery()
          ->getResult()
          ;

        }

}
