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
    private ?Hunt $Hunt = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Player = null;

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
        return $this->Hunt;
    }

    public function setHunt(?Hunt $Hunt): static
    {
        $this->Hunt = $Hunt;

        return $this;
    }

    public function getPlayer(): ?User
    {
        return $this->Player;
    }

    public function setPlayer(?User $Player): static
    {
        $this->Player = $Player;

        return $this;
    }
}
