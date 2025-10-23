<?php

namespace App\Entity;

use App\Repository\CodeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: CodeRepository::class)]
class Code
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 6, unique: true)]
    private ?string $code = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable]
    private ?\DateTime $expireAt = null;

    #[ORM\OneToOne(inversedBy: 'code')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hunt $hunt = null;

    #[ORM\OneToOne(mappedBy: 'code', cascade: ['persist', 'remove'])]
    private ?TeamPlayer $teamPlayer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
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
        $this->hunt = $hunt;

        return $this;
    }

    public function getTeamPlayer(): ?TeamPlayer
    {
        return $this->teamPlayer;
    }

    public function setTeamPlayer(?TeamPlayer $teamPlayer): static
    {
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
}
