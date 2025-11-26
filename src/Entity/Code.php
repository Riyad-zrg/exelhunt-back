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
use App\Repository\CodeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CodeRepository::class)]
#[Assert\Callback([Code::class, 'validateRelation'])]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_USER')"),
        new Put(security: "is_granted('ROLE_USER')"),
        new Patch(security: "is_granted('ROLE_USER')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['code:read']],
    denormalizationContext: ['groups' => ['code:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['code' => 'exact', 'hunt.id' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['createdAt', 'expireAt', 'code'], arguments: ['orderParameterName' => 'order'])]
class Code
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['code:read', 'hunt:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 6, unique: true)]
    #[Groups(['code:read', 'code:write', 'hunt:read', 'teamPlayer:read', 'teamCreator:read'])]
    private ?int $code = null;

    #[ORM\Column]
    #[Groups(['code:read', 'code:write', 'hunt:read', 'teamPlayer:read', 'teamCreator:read'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column]
    #[Groups(['code:read', 'code:write', 'hunt:read', 'teamPlayer:read', 'teamCreator:read'])]
    private ?\DateTime $expireAt = null;

    #[ORM\OneToOne(inversedBy: 'code', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Hunt $hunt = null;

    #[ORM\OneToOne(inversedBy: 'code', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?TeamPlayer $teamPlayer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getExpireAt(): ?\DateTime
    {
        return $this->expireAt;
    }

    public function setExpireAt(\DateTime $expireAt): static
    {
        $this->expireAt = $expireAt;

        return $this;
    }

    public function getHunt(): ?Hunt
    {
        return $this->hunt;
    }

    public function setHunt(?Hunt $hunt): static
    {
        // If setting a hunt, clear teamPlayer to respect XOR constraint
        if (null !== $hunt && null !== $this->teamPlayer) {
            $this->setTeamPlayer(null);
        }

        // unset the owning side of the relation if necessary
        if (null === $hunt && null !== $this->hunt) {
            $this->hunt->setCode(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $hunt && $hunt->getCode() !== $this) {
            $hunt->setCode($this);
        }

        $this->hunt = $hunt;

        return $this;
    }

    public function getTeamPlayer(): ?TeamPlayer
    {
        return $this->teamPlayer;
    }

    public function setTeamPlayer(?TeamPlayer $teamPlayer): static
    {
        // If setting a teamPlayer, clear hunt to respect XOR constraint
        if (null !== $teamPlayer && null !== $this->hunt) {
            $this->setHunt(null);
        }

        // unset the owning side of the relation if necessary
        if (null === $teamPlayer && null !== $this->teamPlayer) {
            $this->teamPlayer->setCode(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $teamPlayer && $teamPlayer->getCode() !== $this) {
            $teamPlayer->setCode($this);
        }

        $this->teamPlayer = $teamPlayer;

        return $this;
    }

    /**
     * Validation callback to ensure a Code is associated with either a Hunt or a TeamPlayer, but not both or none.
     */
    public static function validateRelation(Code $code, ExecutionContextInterface $context): void
    {
        $hasHunt = null !== $code->getHunt();
        $hasTeamPlayer = null !== $code->getTeamPlayer();

        if (!$hasHunt && !$hasTeamPlayer) {
            $context->buildViolation('A code must be associated with either a hunt or a team player.')
                ->atPath('hunt')
                ->addViolation();
        }

        if ($hasHunt && $hasTeamPlayer) {
            $context->buildViolation('A code cannot be associated with both a hunt and a team player.')
                ->atPath('teamPlayer')
                ->addViolation();
        }
    }
}
