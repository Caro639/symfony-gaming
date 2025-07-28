<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

        public function findBestPlayCategories(int $limit = null)
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.games', 'g')
            ->join('g.ownedByUser', 'gBu')
            ->groupBy('c.id')
            ->orderBy ('SUM(gBu.gameTime)', 'DESC')
            ->setMaxResults($limit);

        if ($limit !== null) {
            // LIMIT $limit
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public  function  findByGameCategory(Game $game, int $limit = null)
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.games', 'cG')
            ->where('cG = :categ')
            ->setParameter('categ', $game);

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();

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
}
