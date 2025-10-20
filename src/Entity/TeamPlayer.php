<?php

namespace App\Entity;

use App\Repository\TeamPlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, ParticipationTeam>
     */
    #[ORM\OneToMany(targetEntity: ParticipationTeam::class, mappedBy: 'teamPlayer')]
    private Collection $participationTeams;

    public function __construct()
    {
        parent::__construct();
        $this->participationTeams = new ArrayCollection();
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

    /**
     * @return Collection<int, ParticipationTeam>
     */
    public function getParticipationTeams(): Collection
    {
        return $this->participationTeams;
    }

    public function addParticipationTeam(ParticipationTeam $participationTeam): static
    {
        if (!$this->participationTeams->contains($participationTeam)) {
            $this->participationTeams->add($participationTeam);
            $participationTeam->setTeamPlayer($this);
        }

        return $this;
    }

    public function removeParticipationTeam(ParticipationTeam $participationTeam): static
    {
        if ($this->participationTeams->removeElement($participationTeam)) {
            // set the owning side to null (unless already changed)
            if ($participationTeam->getTeamPlayer() === $this) {
                $participationTeam->setTeamPlayer(null);
            }
        }

        return $this;
    }
}
