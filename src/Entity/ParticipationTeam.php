<?php

namespace App\Entity;

use App\Repository\ParticipationTeamRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationTeamRepository::class)]
class ParticipationTeam
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $teamGlobalTime = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $averageTeamTime = null;

    #[ORM\ManyToOne(inversedBy: 'participationTeams')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TeamPlayer $teamPlayer = null;

    #[ORM\ManyToOne(inversedBy: 'participationTeams')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hunt $hunt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamGlobalTime(): ?\DateTimeImmutable
    {
        return $this->teamGlobalTime;
    }

    public function setTeamGlobalTime(?\DateTimeImmutable $teamGlobalTime): static
    {
        $this->teamGlobalTime = $teamGlobalTime;

        return $this;
    }

    public function getAverageTeamTime(): ?\DateTimeImmutable
    {
        return $this->averageTeamTime;
    }

    public function setAverageTeamTime(?\DateTimeImmutable $averageTeamTime): static
    {
        $this->averageTeamTime = $averageTeamTime;

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
