<?php

namespace App\Entity;

use App\Repository\TeamPlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamPlayerRepository::class)]
class TeamPlayer extends Team
{
    #[ORM\Column]
    private ?int $nbPlayers = null;

    #[ORM\Column]
    private ?bool $isPublic = null;

    #[ORM\OneToOne(inversedBy: 'teamPlayer', cascade: ['persist', 'remove'])]
    private ?Code $code = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $teamGlobalTime = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTime $averageGlobalTime = null;

    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'teamPlayer')]
    private Collection $participation;

    #[ORM\ManyToOne(inversedBy: 'teamPlayers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hunt $hunt = null;

    public function __construct()
    {
        parent::__construct();
        $this->participation = new ArrayCollection();
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

    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
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

    public function getTeamGlobalTime(): ?\DateTime
    {
        return $this->teamGlobalTime;
    }

    public function setTeamGlobalTime(?\DateTime $teamGlobalTime): static
    {
        $this->teamGlobalTime = $teamGlobalTime;

        return $this;
    }

    public function getAverageGlobalTime(): ?\DateTime
    {
        return $this->averageGlobalTime;
    }

    public function setAverageGlobalTime(?\DateTime $averageGlobalTime): static
    {
        $this->averageGlobalTime = $averageGlobalTime;

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipation(): Collection
    {
        return $this->participation;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participation->contains($participation)) {
            $this->participation->add($participation);
            $participation->setTeamPlayer($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participation->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getTeamPlayer() === $this) {
                $participation->setTeamPlayer(null);
            }
        }

        return $this;
    }

    public function getHunt(): ?Hunt
    {
        return $this->hunt;
    }

    public function setHunt(?Hunt $hunt): static
    {
        $this->hunt = $hunt;

        return $this;
    }
}
