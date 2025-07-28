<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReviewRepository;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\Api\Review\PostReviewController;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new \ApiPlatform\Metadata\GetCollection(),
        new \ApiPlatform\Metadata\Get(),
        new \ApiPlatform\Metadata\Post(
            uriTemplate: '/reviews/{id}', // id => id d'un jeu et non d'un commentaire !
            controller: PostReviewController::class,
            denormalizationContext: [
                'groups' => 'review:post',
            ],
        ),
        new \ApiPlatform\Metadata\Put(),
        new \ApiPlatform\Metadata\Patch()
    ]
)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['review:post'])]
    #[Assert\NotBlank(message: "Le contenu de la review ne peut pas être vide")]
    #[Assert\Length(
        min: 10,
        max: 2000,
        minMessage: "La review doit contenir au moins {{ limit }} caractères",
        maxMessage: "La review ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $content = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?int $downVote = 0;

    #[ORM\Column]
    private ?int $upVote = 0;

    #[ORM\Column(nullable: true)]
    #[Groups(['review:post'])]
    #[Assert\Range(
        min: 0,
        max: 5,
        notInRangeMessage: "La note doit être comprise entre {{ min }} et {{ max }}"
    )]
    private ?float $rating = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Un utilisateur doit être associé à la review")]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Un jeu doit être associé à la review")]
    private ?Game $game = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
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

    public function getDownVote(): ?int
    {
        return $this->downVote;
    }

    public function setDownVote(int $downVote): static
    {
        $this->downVote = $downVote;

        return $this;
    }

    public function getUpVote(): ?int
    {
        return $this->upVote;
    }

    public function setUpVote(int $upVote): static
    {
        $this->upVote = $upVote;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): static
    {
        $this->rating = $rating;

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

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Calcule le score total (upVotes - downVotes)
     */
    public function getTotalScore(): int
    {
        return $this->upVote - $this->downVote;
    }

    /**
     * Incrémente le nombre d'upVotes
     */
    public function incrementUpVote(): static
    {
        $this->upVote++;
        return $this;
    }

    /**
     * Incrémente le nombre de downVotes
     */
    public function incrementDownVote(): static
    {
        $this->downVote++;
        return $this;
    }

    /**
     * Retourne le pourcentage de votes positifs
     */
    public function getPositiveVotePercentage(): float
    {
        $total = $this->upVote + $this->downVote;
        if ($total === 0) {
            return 0;
        }
        return round(($this->upVote / $total) * 100, 1);
    }
}
