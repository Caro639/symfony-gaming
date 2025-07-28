<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\PublisherRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Slugify\SlugInterface;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: PublisherRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(operations: [
    new Get(
        normalizationContext: [
            'groups' => [
                'publisher:item',
                'game:collection',
            ]
        ]
    ),
    new GetCollection(
        order: ['createdAt' => 'DESC'],
        normalizationContext: [
            'groups' => [
                'publisher:collection',
            ]
        ]
    ),
    new Post(
        normalizationContext: [
            'groups' => 'publisher:item',
        ],
        denormalizationContext: [
            'groups' => 'publisher:post',
        ]
    ),
    new Put(
        normalizationContext: [
            'groups' => 'publisher:item',
        ],
        denormalizationContext: [
            'groups' => 'publisher:post',
        ]
    ),
    new Patch(
        normalizationContext: [
            'groups' => 'publisher:item',
        ],
        denormalizationContext: [
            'groups' => 'publisher:post',
        ]
    )
])]
class Publisher implements SlugInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['publisher:item', 'publisher:collection'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['publisher:post', 'publisher:item'])]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    #[Groups(['publisher:post', 'publisher:item', 'publisher:collection'])]
    #[Assert\NotBlank(message: 'Le nom doit être renseigné !')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le publisher doit faire au moins 3 caractères !',
        maxMessage: 'Le publisher doit faire maximum 255 caractères !',
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['publisher:item', 'publisher:collection'])]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    #[Groups(['publisher:post', 'publisher:item'])]
    #[Assert\NotBlank(message: 'Le site doit être renseigné !')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Le site doit faire au moins 3 caractères !',
        maxMessage: 'Le site doit faire maximum 255 caractères !',
    )]
    private ?string $website = null;


    #[ORM\ManyToOne(inversedBy: 'publishers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['publisher:post', 'publisher:item', 'publisher:collection'])]
    private ?Country $country = null;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'publisher')]
    #[Groups('publisher:item')]
    private Collection $games;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function __construct()
    {
        $this->games = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setPublisher($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getPublisher() === $this) {
                $game->setPublisher(null);
            }
        }

        return $this;
    }

    public function getFields(): ?string
    {
        return $this->name;
    }
}
