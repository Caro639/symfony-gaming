<?php

namespace App\Controller;

use App\Entity\Review;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class VoteController extends AbstractController
{
    #[Route('/vote/review/{id}/{type}', name: 'app_vote_review', methods: ['POST'])]
    public function voteReview(
        int $id,
        string $type,
        ReviewRepository $reviewRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse {
        // Vérifier que la requête est bien en AJAX
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['error' => 'Cette action n\'est disponible qu\'en AJAX'], 400);
        }

        // Vérifier que le type de vote est valide
        if (!in_array($type, ['up', 'down'])) {
            return new JsonResponse(['error' => 'Type de vote invalide'], 400);
        }

        // Récupérer la review
        $review = $reviewRepository->find($id);
        if (!$review) {
            return new JsonResponse(['error' => 'Review non trouvée'], 404);
        }

        try {
            // Appliquer le vote
            if ($type === 'up') {
                $review->incrementUpVote();
            } else {
                $review->incrementDownVote();
            }

            $entityManager->persist($review);

            // Sauvegarder en base
            $entityManager->flush();

            // Retourner les nouveaux compteurs avec des informations supplémentaires
            return new JsonResponse([
                'success' => true,
                'upVotes' => $review->getUpVote(),
                'downVotes' => $review->getDownVote(),
                'totalScore' => $review->getTotalScore(),
                'positivePercentage' => $review->getPositiveVotePercentage(),
                'reviewId' => $review->getId(),
                'message' => $type === 'up' ? 'Vote positif enregistré !' : 'Vote négatif enregistré !'
            ]);

        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Erreur lors de l\'enregistrement du vote',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
