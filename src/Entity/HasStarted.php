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
use App\Repository\HasStartedRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HasStartedRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER') and object.getPlayer() == user"),
        new Patch(security: "is_granted('ROLE_USER') and object.getPlayer() == user"),
        new Delete(security: "is_granted('ROLE_ADMIN') or object.getPlayer() == user"),
    ],
    normalizationContext: ['groups' => ['has_started:read']],
    denormalizationContext: ['groups' => ['has_started:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['player.id' => 'exact', 'puzzle.id' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['startedAt'])]
#[ApiFilter(OrderFilter::class, properties: ['startedAt'], arguments: ['orderParameterName' => 'order'])]
class HasStarted
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['has_started:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['has_started:read', 'has_started:write'])]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\ManyToOne(inversedBy: 'startPuzzle')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['has_started:read', 'has_started:write'])]
    private ?User $player = null;

    #[ORM\ManyToOne(inversedBy: 'hasStarteds')]
    #[Groups(['has_started:read', 'has_started:write'])]
    private ?Puzzle $puzzle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

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

    public function getPuzzle(): ?Puzzle
    {
        return $this->puzzle;
    }

    public function setPuzzle(?Puzzle $puzzle): static
    {
        $this->puzzle = $puzzle;

        return $this;
    }
}
