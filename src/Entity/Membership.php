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
use App\Repository\MembershipRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MembershipRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER')"),
        new Patch(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['membership:read']],
    denormalizationContext: ['groups' => ['membership:write']]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'member.id' => 'exact',
    'team.id' => 'exact',
    'role' => 'partial',
])]
#[ApiFilter(DateFilter::class, properties: ['joinedAt'])]
#[ApiFilter(OrderFilter::class, properties: ['joinedAt'], arguments: ['orderParameterName' => 'order'])]
class Membership
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['membership:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['membership:read', 'membership:write'])]
    private array $role = [];
    #[ORM\Column]
    #[Groups(['membership:read', 'membership:write'])]
    private ?\DateTimeImmutable $joinedAt = null;

    #[ORM\ManyToOne(inversedBy: 'memberships')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['membership:read', 'membership:write'])]
    private ?User $Member = null;

    #[ORM\ManyToOne(inversedBy: 'memberships')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['membership:read', 'membership:write'])]
    private ?Team $Team = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?array
    {
        return $this->role;
    }

    public function setRole(array $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getMember(): ?User
    {
        return $this->Member;
    }

    public function setMember(?User $Member): static
    {
        $this->Member = $Member;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->Team;
    }

    public function setTeam(?Team $Team): static
    {
        $this->Team = $Team;

        return $this;
    }

    public function getJoinedAt(): ?\DateTimeImmutable
    {
        return $this->joinedAt;
    }

    public function setJoinedAt(?\DateTimeImmutable $joinedAt): void
    {
        $this->joinedAt = $joinedAt;
    }
}
