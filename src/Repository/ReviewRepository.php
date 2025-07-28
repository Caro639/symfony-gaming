<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function findAverageRatingByGame(Game $game): float
    {
        $result = $this->createQueryBuilder('r')
            ->select('AVG(r.rating)')
            ->where('r.game = :game')
            ->setParameter('game', $game)
            ->getQuery()
            ->getSingleScalarResult();

        // Si aucune review n'existe, retourner 0.0
        return $result ?? 0.0;
    }

    public function findReviewByGame(Game $game, ?int $limit = null)
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.game = :game')
            ->setParameter('game', $game)
            ->orderBy('r.createdAt', 'DESC');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Compte le nombre de reviews pour un jeu
     */
    public function countReviewsByGame(Game $game): int
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.game = :game')
            ->setParameter('game', $game)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Vérifie si un jeu a des reviews
     */
    public function hasReviews(Game $game): bool
    {
        return $this->countReviewsByGame($game) > 0;
    }

    /**
     * Récupère les reviews les mieux notées pour un jeu
     */
    public function findTopRatedReviewsByGame(Game $game, ?int $limit = null)
    {
        $qb = $this->createQueryBuilder('r')
            ->where('r.game = :game')
            ->setParameter('game', $game)
            ->orderBy('(r.upVote - r.downVote)', 'DESC')
            ->addOrderBy('r.rating', 'DESC')
            ->addOrderBy('r.createdAt', 'DESC');

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Review[] Returns an array of Review objects
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

    //    public function findOneBySomeField($value): ?Review
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
