<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\HuntRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HuntRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER')"),
        new Patch(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['hunt:read']],
    denormalizationContext: ['groups' => ['hunt:write']]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'title' => 'partial',
    'description' => 'partial',
    'visibility' => 'exact',
    'createdBy.id' => 'exact',
    'location.city' => 'partial',
    'location.country' => 'partial',
])]
#[ApiFilter(DateFilter::class, properties: ['createdAt', 'updatedAt'])]
#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'updatedAt', 'nbPlayers', 'title'], arguments: ['orderParameterName' => 'order'])]
class Hunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['hunt:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['hunt:read', 'hunt:write'])]
    private ?string $title = null;

    #[ORM\Column(length: 500)]
    #[Groups(['hunt:read', 'hunt:write'])]
    private ?string $description = null;

    #[ORM\Column(length: 15)]
    #[Groups(['hunt:read', 'hunt:write'])]
    private ?string $visibility = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['hunt:read', 'hunt:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['hunt:read', 'hunt:write'])]
    private ?\DateTime $updatedAt = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['hunt:read', 'hunt:write'])]
    private ?string $avatar = null;

    #[ORM\Column]
    #[Groups(['hunt:read', 'hunt:write'])]
    private ?int $nbPlayers = null;

    #[ORM\ManyToOne(inversedBy: 'hunts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['hunt:read', 'hunt:write'])]
    private ?TeamCreator $createdBy = null;

    #[ORM\ManyToOne(inversedBy: 'hunts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['hunt:read', 'hunt:write'])]
    private ?Address $location = null;

    /**
     * @var Collection<int, Puzzle>
     */
    #[ORM\OneToMany(targetEntity: Puzzle::class, mappedBy: 'hunt', orphanRemoval: true)]
    #[Groups(['hunt:read'])]
    private Collection $puzzles;

    #[ORM\OneToOne(mappedBy: 'Hunt')]
    #[Groups(['hunt:read'])]
    private Code $code;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['hunt:read'])]
    private ?bool $isTeamPlayable = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['hunt:read'])]
    private ?int $teamPlayerMax = null;

    /**
     * @var Collection<int, TeamPlayer>
     */
    #[ORM\OneToMany(targetEntity: TeamPlayer::class, mappedBy: 'hunt', orphanRemoval: true)]
    #[Groups(['hunt:read'])]
    private Collection $teamPlayers;

    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'hunt')]
    #[Groups(['hunt:read'])]
    private Collection $participations;

    public function __construct()
    {
        $this->puzzles = new ArrayCollection();
        $this->teamPlayers = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?Code
    {
        return $this->code;
    }

    public function setCode(?Code $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getNbPlayers(): ?int
    {
        return $this->nbPlayers;
    }

    public function setNbPlayers(int $nbPlayers): static
    {
        $this->nbPlayers = $nbPlayers;

        return $this;
    }

    public function getCreatedBy(): ?TeamCreator
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?TeamCreator $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getLocation(): ?Address
    {
        return $this->location;
    }

    public function setLocation(?Address $location): static
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection<int, Puzzle>
     */
    public function getPuzzles(): Collection
    {
        return $this->puzzles;
    }

    public function addPuzzle(Puzzle $puzzle): static
    {
        if (!$this->puzzles->contains($puzzle)) {
            $this->puzzles->add($puzzle);
            $puzzle->setHunt($this);
        }

        return $this;
    }

    public function removePuzzle(Puzzle $puzzle): static
    {
        if ($this->puzzles->removeElement($puzzle)) {
            // set the owning side to null (unless already changed)
            if ($puzzle->getHunt() === $this) {
                $puzzle->setHunt(null);
            }
        }

        return $this;
    }

    public function isTeamPlayable(): ?bool
    {
        return $this->isTeamPlayable;
    }

    public function setIsTeamPlayable(bool $isTeamPlayable): static
    {
        $this->isTeamPlayable = $isTeamPlayable;

        return $this;
    }

    public function getTeamPlayerMax(): ?int
    {
        return $this->teamPlayerMax;
    }

    public function setTeamPlayerMax(?int $teamPlayerMax): static
    {
        $this->teamPlayerMax = $teamPlayerMax;

        return $this;
    }

    /**
     * @return Collection<int, TeamPlayer>
     */
    public function getTeamPlayers(): Collection
    {
        return $this->teamPlayers;
    }

    public function addTeamPlayer(TeamPlayer $teamPlayer): static
    {
        if (!$this->teamPlayers->contains($teamPlayer)) {
            $this->teamPlayers->add($teamPlayer);
            $teamPlayer->setHunt($this);
        }

        return $this;
    }

    public function removeTeamPlayer(TeamPlayer $teamPlayer): static
    {
        if ($this->teamPlayers->removeElement($teamPlayer)) {
            // set the owning side to null (unless already changed)
            if ($teamPlayer->getHunt() === $this) {
                $teamPlayer->setHunt(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setHunt($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getHunt() === $this) {
                $participation->setHunt(null);
            }
        }

        return $this;
    }
}
