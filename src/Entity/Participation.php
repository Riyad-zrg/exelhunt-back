<?php

namespace App\Entity;

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
use App\Repository\ParticipationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER')"),
        new Patch(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['participation:read']],
    denormalizationContext: ['groups' => ['participation:write']]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'hunt.id' => 'exact',
    'player.id' => 'exact',
    'tracking' => 'partial',
])]
#[ApiFilter(OrderFilter::class, properties: ['globalTime', 'tracking'], arguments: ['orderParameterName' => 'order'])]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['participation:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    #[Groups(['participation:read', 'participation:write'])]
    private ?string $tracking = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups(['participation:read', 'participation:write'])]
    private ?\DateTime $globalTime = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['participation:read', 'participation:write'])]
    private ?Hunt $Hunt = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['participation:read', 'participation:write'])]
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
