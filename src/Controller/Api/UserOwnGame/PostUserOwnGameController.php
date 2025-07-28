<?php

declare(strict_types=1);

namespace App\Controller\Api\UserOwnGame;

use App\Entity\UserOwnGame;
use App\Repository\GameRepository;
use App\Repository\UserOwnGameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PostUserOwnGameController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    public function __invoke(
        string $gameId,
        Request $request,
        GameRepository $gameRepository,
        UserOwnGameRepository $userOwnGameRepository,
        EntityManagerInterface $em,
        SerializerInterface $serializer
    ): Response {
        $data = json_decode($request->getContent(), true) ?? [];

        if (null === $game = $gameRepository->findOneBy(['id' => $gameId])) {
            return new Response('Jeu incorrect', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user = $this->getUser();

        // Vérifier si l'utilisateur possède déjà ce jeu
        $existingUserOwnGame = $userOwnGameRepository->findOneBy([
            'user' => $user,
            'game' => $game
        ]);

        if ($existingUserOwnGame) {
            return new Response('Vous possédez déjà ce jeu', Response::HTTP_CONFLICT);
        }

        // Valeurs par défaut avec possibilité de surcharge via JSON
        $gameTime = $data['gameTime'] ?? 0;
        $isInstalled = $data['isInstalled'] ?? false;

        // Validation des données
        if (!is_int($gameTime) || $gameTime < 0) {
            return new Response('gameTime doit être un entier positif', Response::HTTP_BAD_REQUEST);
        }
        if (!is_bool($isInstalled)) {
            return new Response('isInstalled doit être un booléen', Response::HTTP_BAD_REQUEST);
        }

        $userOwnGame = (new UserOwnGame())
            ->setGame($game)
            ->setUser($user)
            ->setGameTime($gameTime)        // Temps de jeu (défaut: 0)
            ->setIsInstalled($isInstalled); // Statut installation (défaut: false)
        // ->setContent($data['content'])
        // ->setRating($data['rating'])
        // ->setCreatedAt(new DateTimeImmutable());

        $em->persist($userOwnGame);
        $em->flush();

        return new Response($serializer->serialize(
            $userOwnGame,
            'json',
            ['groups' => ['UserOwnGame:item']]
        ), Response::HTTP_CREATED);
    }
}
