<?php

namespace App\Entity;

use DateTimeImmutable;
use ApiPlatform\Metadata\Link;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserOwnGameRepository;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\Api\UserOwnGame\PostUserOwnGameController;

#[ORM\Entity(repositoryClass: UserOwnGameRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new \ApiPlatform\Metadata\GetCollection(),
        new \ApiPlatform\Metadata\Get(),
        new \ApiPlatform\Metadata\Post(
            uriTemplate: '/api/user_own_games/{gameId}',
            uriVariables: [
                'gameId' => new Link(
                    toProperty: 'id',
                    fromClass: Game::class
                )
            ],
            controller: PostUserOwnGameController::class,
            denormalizationContext: [
                'groups' => '',
            ],
        ),
        new \ApiPlatform\Metadata\Put(),
        new \ApiPlatform\Metadata\Patch()
    ]
)]
class UserOwnGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?int $gameTime = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $lastUsedAt = null;

    #[ORM\Column]
    private ?bool $isInstalled = null;

    #[ORM\ManyToOne(inversedBy: 'ownedByUser')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull(message: "Un jeu doit être associé à la user own game")]
    private ?Game $game = null;

    #[ORM\ManyToOne(inversedBy: 'ownedGames')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotNull(message: "Un utilisateur doit être associé à user own game")]
    private ?User $user = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function setLastUsedAtValue(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getGameTime(): ?int
    {
        return $this->gameTime;
    }

    public function setGameTime(int $gameTime): static
    {
        $this->gameTime = $gameTime;

        return $this;
    }

    public function getLastUsedAt(): ?DateTimeImmutable
    {
        return $this->lastUsedAt;
    }

    public function setLastUsedAt(?DateTimeImmutable $lastUsedAt): static
    {
        $this->lastUsedAt = $lastUsedAt;

        return $this;
    }

    public function isInstalled(): ?bool
    {
        return $this->isInstalled;
    }

    public function setIsInstalled(bool $isInstalled): static
    {
        $this->isInstalled = $isInstalled;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
