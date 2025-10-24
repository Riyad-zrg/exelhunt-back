<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $tracking = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $globalTime = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hunt $hunt = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $player = null;

    #[ORM\ManyToOne(inversedBy: 'participation')]
    private ?TeamPlayer $teamPlayer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTracking(): ?string
    {
        return $this->tracking;
    }

    public function setTracking(string $tracking): static
    {
        $this->tracking = $tracking;

        return $this;
    }

    public function getGlobalTime(): ?\DateTime
    {
        return $this->globalTime;
    }

    public function setGlobalTime(?\DateTime $globalTime): static
    {
        $this->globalTime = $globalTime;

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

    public function getPlayer(): ?User
    {
        return $this->player;
    }

    public function setPlayer(?User $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getTeamPlayer(): ?TeamPlayer
    {
        return $this->teamPlayer;
    }

    public function setTeamPlayer(?TeamPlayer $teamPlayer): static
    {
        $this->teamPlayer = $teamPlayer;

        return $this;
    }
}
