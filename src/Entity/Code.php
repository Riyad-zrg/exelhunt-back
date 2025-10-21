<?php

namespace App\Entity;

use App\Repository\CodeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CodeRepository::class)]
class Code
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 6, unique: true)]
    private ?int $code = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $expireAt = null;

    #[ORM\OneToOne(inversedBy: 'code')]
    #[ORM\JoinColumn(nullable: false)]
    private ?hunt $hunt = null;

    #[ORM\OneToOne(mappedBy: 'code', cascade: ['persist', 'remove'])]
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

    public function getHunt(): ?hunt
    {
        return $this->hunt;
    }

    public function setHunt(?hunt $hunt): static
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
        if ($teamPlayer === null && $this->teamPlayer !== null) {
            $this->teamPlayer->setCode(null);
        }

        // set the owning side of the relation if necessary
        if ($teamPlayer !== null && $teamPlayer->getCode() !== $this) {
            $teamPlayer->setCode($this);
        }

        $this->teamPlayer = $teamPlayer;

        return $this;
    }
}
