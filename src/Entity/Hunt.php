<?php

namespace App\Entity;

use App\Repository\HuntRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[\AllowDynamicProperties]
#[ORM\Entity(repositoryClass: HuntRepository::class)]
class Hunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 500)]
    private ?string $description = null;

    #[ORM\Column(length: 15)]
    private ?string $visibility = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $avatar = null;

    #[ORM\Column]
    private ?int $nbPlayers = null;

    #[ORM\ManyToOne(inversedBy: 'hunts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TeamCreator $createdBy = null;

    #[ORM\ManyToOne(inversedBy: 'hunts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Address $location = null;

    /**
     * @var Collection<int, Puzzle>
     */
    #[ORM\OneToMany(targetEntity: Puzzle::class, mappedBy: 'hunt', orphanRemoval: true)]
    private Collection $puzzles;

    #[ORM\OneToOne(mappedBy: 'hunt')]
    private ?Code $code = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $isTeamPlayable = null;

    #[ORM\Column(nullable: true)]
    private ?int $teamPlayerMax = null;

    /**
     * @var Collection<int, TeamPlayer>
     */
    #[ORM\OneToMany(targetEntity: TeamPlayer::class, mappedBy: 'hunt', orphanRemoval: true)]
    private Collection $teamPlayers;

    public function __construct()
    {
        $this->puzzles = new ArrayCollection();
        $this->teamPlayers = new ArrayCollection();
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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
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
}
