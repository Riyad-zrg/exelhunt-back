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

#[ORM\Entity(repositoryClass: CodeRepository::class)]
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
    #[Groups(['code:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 6, unique: true)]
    #[Groups(['code:read', 'code:write'])]
    private ?int $code = null;

    #[ORM\Column]
    #[Groups(['code:read', 'code:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['code:read', 'code:write'])]
    private ?\DateTimeImmutable $expireAt = null;

    #[ORM\OneToOne(inversedBy: 'code')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['code:read', 'code:write'])]
    private ?Hunt $Hunt = null;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getExpireAt(): ?\DateTimeImmutable
    {
        return $this->expireAt;
    }

    public function setExpireAt(\DateTimeImmutable $expireAt): static
    {
        $this->expireAt = $expireAt;

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
}
